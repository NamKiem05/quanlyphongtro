<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\ContractSignature;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\HouseSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractService
{
    /**
     * Lấy danh sách hợp đồng
     */
    public function getContracts(array $filters = []): LengthAwarePaginator
    {
        $query = Contract::query()
            ->with([
                'room',
                'tenants',
            ]);

        // Tìm kiếm theo tên phòng hoặc tên người thuê
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->whereHas('room', function ($roomQuery) use ($search) {
                    $roomQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('tenants', function ($tenantQuery) use ($search) {
                        $tenantQuery->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái hợp đồng
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sắp xếp
        switch ($filters['sort'] ?? 'newest') {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'rent_asc':
                $query->orderBy('rent_price', 'asc');
                break;

            case 'rent_desc':
                $query->orderBy('rent_price', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(10)->withQueryString();
    }

    /**
     * Thống kê hợp đồng
     */
    public function getStatistics(): array
    {
        return [
            'total' => Contract::count(),

            'draft' => Contract::where('status', 'draft')->count(),

            'waiting_signature' => Contract::where(
                'status',
                'waiting_signature'
            )->count(),
            'waiting_owner_signature' => Contract::where(
                'status',
                'waiting_owner_signature'
            )->count(),

            'active' => Contract::where('status', 'active')->count(),

            'expired' => Contract::where('status', 'expired')->count(),

            'terminated' => Contract::where('status', 'terminated')->count(),
        ];
    }

    /**
     * Lấy chi tiết hợp đồng
     */
    public function getContractDetail(Contract $contract): Contract
    {
        return $contract->load([
            'room',
            'tenants',
            'contractTenants.tenant',
            'deposits',
            'invoices',
        ]);
    }

    /**
     * Lấy dữ liệu hợp đồng để chỉnh sửa
     */
    public function getContractForEdit(Contract $contract): Contract
    {
        if (!in_array($contract->status, ['draft', 'waiting_signature'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'contract' => 'Hợp đồng này không được phép chỉnh sửa.',
            ]);
        }

        return $contract->load([
            'room',
            'tenants',
            'contractTenants.tenant',
        ]);
    }
    /**
     * Cập nhật hợp đồng
     */
    public function updateContract(Contract $contract, array $data): Contract
    {
        // Kiểm tra trạng thái hợp đồng
        if (!in_array($contract->status, ['draft', 'waiting_signature'])) {
            throw ValidationException::withMessages([
                'contract' => 'Hợp đồng này không được phép chỉnh sửa.',
            ]);
        }

        // Kiểm tra người đại diện có trong danh sách người thuê
        if (!in_array(
            $data['representative_tenant_id'],
            $data['tenant_ids']
        )) {
            throw ValidationException::withMessages([
                'representative_tenant_id' => 'Người đại diện phải nằm trong danh sách người thuê.',
            ]);
        }

        return DB::transaction(function () use ($contract, $data) {

            // Cập nhật thông tin hợp đồng
            $contract->update([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'rent_price' => $data['rent_price'],
                'deposit_amount' => $data['deposit_amount'],
                'billing_day' => $data['billing_day'],
                'terms' => $data['terms'] ?? null,
            ]);

            // Chuẩn bị danh sách người thuê và người đại diện
            $syncData = [];

            foreach ($data['tenant_ids'] as $tenantId) {
                $syncData[$tenantId] = [
                    'is_representative' =>
                    $tenantId == $data['representative_tenant_id'],
                ];
            }

            // Đồng bộ bảng contract_tenants
            $contract->tenants()->sync($syncData);

            return $contract->load([
                'room',
                'tenants',
                'contractTenants.tenant',
            ]);
        });
    }

    /**
     * Xóa hợp đồng
     */
    public function deleteContract(Contract $contract): void
    {
        DB::transaction(function () use ($contract) {

            // Khóa bản ghi hợp đồng trong quá trình kiểm tra và xóa
            $contract = Contract::whereKey($contract->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 1. Chỉ cho phép xóa hợp đồng nháp hoặc chờ ký
            if (!in_array($contract->status, ['draft', 'waiting_signature'])) {
                throw ValidationException::withMessages([
                    'contract' => 'Chỉ được phép xóa hợp đồng nháp hoặc đang chờ ký.',
                ]);
            }

            // 2. Kiểm tra lịch sử giao dịch tiền cọc
            if ($contract->deposits()->exists()) {
                throw ValidationException::withMessages([
                    'contract' => 'Không thể xóa hợp đồng vì đã phát sinh giao dịch tiền cọc.',
                ]);
            }

            // 3. Kiểm tra hóa đơn
            if ($contract->invoices()->exists()) {
                throw ValidationException::withMessages([
                    'contract' => 'Không thể xóa hợp đồng vì đã phát sinh hóa đơn.',
                ]);
            }

            // 4. Xóa hợp đồng
            // Các bản ghi contract_tenants sẽ được xóa theo cascade
            // Người thuê vẫn được giữ nguyên
            $contract->delete();
        });
    }


    /**
     * Lấy dữ liệu phục vụ tạo hợp đồng
     */
    public function getDataForCreate(): array
    {
        // 1. Lấy danh sách phòng có thể tạo hợp đồng
        $rooms = Room::query()
            ->where('status', 'available')
            ->whereDoesntHave('contracts', function ($query) {
                $query->whereIn('status', [
                    'waiting_signature',
                    'active',
                ]);
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'floor',
                'area',
                'rent_price',
            ]);

        // 2. Lấy danh sách người thuê chưa có hợp đồng
        // đang chờ ký hoặc đang có hiệu lực
        $tenants = \App\Models\Tenant::query()
            ->whereDoesntHave('contracts', function ($query) {
                $query->whereIn('status', [
                    'waiting_signature',
                    'active',
                ]);
            })
            ->orderBy('full_name')
            ->get([
                'id',
                'full_name',
                'phone',
                'identity_number',
            ]);

        return [
            'rooms' => $rooms,
            'tenants' => $tenants,
        ];
    }


    /**
     * Tạo hợp đồng mới
     */
    public function createContract(array $data, int $ownerId): Contract
    {
        return DB::transaction(function () use ($data, $ownerId) {

            // 1. Kiểm tra và khóa phòng
            $room = Room::whereKey($data['room_id'])
                ->lockForUpdate()
                ->first();

            if (!$room || $room->status !== 'available') {
                throw ValidationException::withMessages([
                    'room_id' => 'Phòng không tồn tại hoặc hiện không khả dụng.',
                ]);
            }

            // 2. Kiểm tra phòng đã có hợp đồng chờ ký hoặc đang hiệu lực chưa
            $hasContract = $room->contracts()
                ->whereIn('status', ['waiting_signature', 'active'])
                ->exists();

            if ($hasContract) {
                throw ValidationException::withMessages([
                    'room_id' => 'Phòng này đã có hợp đồng đang chờ ký hoặc đang có hiệu lực.',
                ]);
            }

            // 3. Kiểm tra người đại diện
            $tenantIds = array_map('intval', $data['tenant_ids']);

            $representativeId = (int) $data['representative_tenant_id'];

            if (!in_array($representativeId, $tenantIds, true)) {
                throw ValidationException::withMessages([
                    'representative_tenant_id' =>
                    'Người đại diện phải nằm trong danh sách người thuê.',
                ]);
            }

            // 4. Khóa các bản ghi người thuê
            $tenants = \App\Models\Tenant::whereIn('id', $tenantIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($tenants->count() !== count($tenantIds)) {
                throw ValidationException::withMessages([
                    'tenant_ids' => 'Một hoặc nhiều người thuê không tồn tại.',
                ]);
            }

            // 5. Kiểm tra người thuê đã có hợp đồng chờ ký hoặc đang hiệu lực chưa
            $busyTenant = $tenants->first(function ($tenant) {
                return $tenant->contracts()
                    ->whereIn('contracts.status', [
                        'waiting_signature',
                        'active',
                    ])
                    ->exists();
            });

            if ($busyTenant) {
                throw ValidationException::withMessages([
                    'tenant_ids' =>
                    'Có người thuê đã thuộc một hợp đồng đang chờ ký hoặc đang có hiệu lực.',
                ]);
            }

            // 6. Tạo hợp đồng
            $contract = Contract::create([
                'owner_id' => $ownerId,
                'room_id' => $room->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'rent_price' => $data['rent_price'],
                'deposit_amount' => $data['deposit_amount'],
                'billing_day' => $data['billing_day'],
                'terms' => $data['terms'] ?? null,
                'status' => 'draft',
            ]);

            // 7. Gắn người thuê và xác định người đại diện
            $syncData = [];

            foreach ($tenantIds as $tenantId) {
                $syncData[$tenantId] = [
                    'is_representative' => $tenantId === $representativeId,
                ];
            }

            $contract->tenants()->sync($syncData);

            // 8. Sao chép dịch vụ của phòng vào hợp đồng
            $roomServices = $room->roomServices()
                ->with('service')
                ->get();
            logger()->info('Kiểm tra dịch vụ phòng khi tạo hợp đồng', [
                'room_id' => $room->id,
                'room_name' => $room->name,
                'room_services' => $roomServices->toArray(),
            ]);

            foreach ($roomServices as $roomService) {
                $contract->contractServices()->create([
                    'service_id' => $roomService->service_id,
                    'service_name' => $roomService->service->name,
                    'type' => $roomService->service->type,
                    'unit' => $roomService->service->unit,
                    'quantity' => $roomService->quantity,
                    'unit_price' => $roomService->unit_price,
                ]);
            }


            // 8. Trả về hợp đồng cùng thông tin liên quan
            return $contract->load([
                'room',
                'tenants',
                'contractTenants.tenant',
                'contractServices',
            ]);
        });
    }

    /**
     * Chuyển hợp đồng từ bản nháp sang chờ ký
     */

    public function sendForSignature(Contract $contract): array
    {
        return DB::transaction(function () use ($contract) {

            // Khóa hợp đồng để tránh hai request xử lý cùng lúc
            $contract = Contract::whereKey($contract->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($contract->status !== 'draft') {
                throw ValidationException::withMessages([
                    'contract' => 'Chỉ hợp đồng bản nháp mới được gửi chờ ký.',
                ]);
            }

            // Khóa phòng
            $room = Room::whereKey($contract->room_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Kiểm tra phòng có hợp đồng khác đang chờ ký hoặc hiệu lực không
            $hasRoomContract = $room->contracts()
                ->where('contracts.id', '!=', $contract->id)
                ->whereIn('status', ['waiting_signature', 'active'])
                ->exists();

            if ($hasRoomContract) {
                throw ValidationException::withMessages([
                    'contract' => 'Phòng này đã có hợp đồng đang chờ ký hoặc đang có hiệu lực.',
                ]);
            }

            // Lấy và khóa danh sách người thuê
            $tenantIds = $contract->tenants()
                ->orderBy('tenants.id')
                ->pluck('tenants.id')
                ->toArray();

            if (empty($tenantIds)) {
                throw ValidationException::withMessages([
                    'contract' => 'Hợp đồng phải có ít nhất một người thuê.',
                ]);
            }

            \App\Models\Tenant::whereIn('id', $tenantIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            // Kiểm tra người thuê có thuộc hợp đồng khác đang chờ ký hoặc hiệu lực không
            $hasBusyTenant = $contract->tenants()
                ->whereHas('contracts', function ($query) use ($contract) {
                    $query->where('contracts.id', '!=', $contract->id)
                        ->whereIn('status', [
                            'waiting_signature',
                            'active',
                        ]);
                })
                ->exists();

            if ($hasBusyTenant) {
                throw ValidationException::withMessages([
                    'contract' => 'Có người thuê đã thuộc một hợp đồng đang chờ ký hoặc đang có hiệu lực.',
                ]);
            }

            // Xác định người đại diện thuê
            $contractTenant = $contract->contractTenants()
                ->where('is_representative', true)
                ->with('tenant')
                ->first();

            $representative = $contractTenant?->tenant;

            if (!$representative) {
                throw ValidationException::withMessages([
                    'contract' => 'Hợp đồng chưa có người đại diện thuê.',
                ]);
            }

            if (
                empty($representative->email) ||
                !filter_var($representative->email, FILTER_VALIDATE_EMAIL)
            ) {
                throw ValidationException::withMessages([
                    'contract' => 'Người đại diện chưa có địa chỉ email hợp lệ.',
                ]);
            }

            // Tìm chữ ký đã tồn tại của hợp đồng và người đại diện
            $signature = ContractSignature::where('contract_id', $contract->id)
                ->where('tenant_id', $representative->id)
                ->lockForUpdate()
                ->first();

            // Không cho phép gửi lại nếu hợp đồng đã được ký
            if ($signature && $signature->status === 'signed') {
                throw ValidationException::withMessages([
                    'contract' => 'Người đại diện đã ký hợp đồng này.',
                ]);
            }

            // Tạo token ngẫu nhiên
            $plainToken = Str::random(64);

            // Chỉ lưu mã băm vào database
            $tokenHash = hash('sha256', $plainToken);

            $signatureData = [
                'token_hash' => $tokenHash,
                'expires_at' => now()->addHours(48),
                'signature_path' => null,
                'status' => 'pending',
                'signed_at' => null,
                'signed_ip' => null,
                'user_agent' => null,
            ];

            if ($signature) {
                $signature->update($signatureData);
            } else {
                ContractSignature::create([
                    'contract_id' => $contract->id,
                    'tenant_id' => $representative->id,
                    ...$signatureData,
                ]);
            }

            // Chuyển hợp đồng sang trạng thái chờ ký
            // $contract->update([
            //     'status' => 'waiting_signature',
            // ]);

            return [
                'contract' => $contract->fresh([
                    'room',
                    'tenants',
                    'contractServices',
                ]),
                'representative' => $representative,
                'token' => $plainToken,
            ];
        });
    }



    /**
     * Kích hoạt hợp đồng sau khi cả hai bên đã ký.
     */
    public function activateContract(Contract $contract): Contract
    {
        return DB::transaction(function () use ($contract) {

            $contract = Contract::whereKey($contract->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Chỉ kích hoạt khi đang chờ chủ nhà ký
            if ($contract->status !== 'waiting_owner_signature') {
                throw ValidationException::withMessages([
                    'contract' => 'Hợp đồng không ở trạng thái chờ chủ nhà ký.',
                ]);
            }

            // Kiểm tra người đại diện thuê
            $representative = $contract->contractTenants()
                ->where('is_representative', true)
                ->first();

            if (!$representative) {
                throw ValidationException::withMessages([
                    'contract' => 'Hợp đồng chưa có người đại diện.',
                ]);
            }

            // Kiểm tra chữ ký người thuê
            $tenantSignature = ContractSignature::where(
                'contract_id',
                $contract->id
            )
                ->where('tenant_id', $representative->tenant_id)
                ->lockForUpdate()
                ->first();

            if (
                !$tenantSignature ||
                $tenantSignature->status !== 'signed' ||
                !$tenantSignature->signature_path
            ) {
                throw ValidationException::withMessages([
                    'contract' => 'Người thuê chưa ký hợp đồng.',
                ]);
            }

            // Kiểm tra chữ ký chủ nhà
            if (
                !$contract->owner_signature_path ||
                !$contract->owner_signed_at
            ) {
                throw ValidationException::withMessages([
                    'contract' => 'Chủ nhà chưa ký hợp đồng.',
                ]);
            }

            // Kiểm tra file chữ ký chủ nhà tồn tại
            if (!Storage::disk('local')->exists(
                $contract->owner_signature_path
            )) {
                throw ValidationException::withMessages([
                    'contract' => 'Không tìm thấy file chữ ký chủ nhà.',
                ]);
            }

            // Khóa phòng
            $room = Room::whereKey($contract->room_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($room->status !== 'available') {
                throw ValidationException::withMessages([
                    'contract' => 'Phòng hiện không khả dụng nên không thể kích hoạt hợp đồng.',
                ]);
            }

            // Kích hoạt hợp đồng sau khi cả hai bên đã ký
            $contract->update([
                'status' => 'active',
                'signed_at' => now(),
            ]);

            // Cập nhật trạng thái phòng
            $room->update([
                'status' => 'occupied',
            ]);

            return $contract->fresh([
                'room',
                'tenants',
            ]);
        });
    }


    /**
     * Chấm dứt hợp đồng đang có hiệu lực
     */
    public function terminateContract(Contract $contract): Contract
    {
        return DB::transaction(function () use ($contract) {

            $contract = Contract::whereKey($contract->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($contract->status !== 'active') {
                throw ValidationException::withMessages([
                    'contract' => 'Chỉ hợp đồng đang có hiệu lực mới được chấm dứt.',
                ]);
            }

            $room = Room::whereKey($contract->room_id)
                ->lockForUpdate()
                ->firstOrFail();

            $contract->update([
                'status' => 'terminated',
            ]);

            // Chỉ trả phòng về khả dụng nếu không còn hợp đồng active khác
            $hasOtherActiveContract = $room->contracts()
                ->where('contracts.id', '!=', $contract->id)
                ->where('status', 'active')
                ->exists();

            // Không ghi đè trạng thái bảo trì hoặc trạng thái khác
            if (!$hasOtherActiveContract && $room->status === 'occupied') {
                $room->update([
                    'status' => 'available',
                ]);
            }

            return $contract->fresh([
                'room',
                'tenants',
            ]);
        });
    }


    /**
     * Hoàn tất quá trình ký hợp đồng của người thuê.
     */
    public function completeSigning(
        ContractSignature $signature,
        string $signatureData,
        ?string $ip,
        ?string $userAgent
    ): Contract {
        // Kiểm tra định dạng Data URL
        if (!preg_match(
            '/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/',
            $signatureData,
            $matches
        )) {
            throw ValidationException::withMessages([
                'signature' => 'Dữ liệu chữ ký không đúng định dạng PNG.',
            ]);
        }

        // Giải mã Base64
        $imageData = base64_decode($matches[1], true);

        if ($imageData === false || strlen($imageData) > 2 * 1024 * 1024) {
            throw ValidationException::withMessages([
                'signature' => 'Ảnh chữ ký không hợp lệ hoặc vượt quá 2MB.',
            ]);
        }

        // Kiểm tra nội dung thực sự là ảnh PNG
        $imageInfo = getimagesizefromstring($imageData);

        if (
            $imageInfo === false ||
            $imageInfo['mime'] !== 'image/png'
        ) {
            throw ValidationException::withMessages([
                'signature' => 'File chữ ký không phải ảnh PNG hợp lệ.',
            ]);
        }

        $signaturePath = null;

        try {
            return DB::transaction(function () use (
                $signature,
                $imageData,
                $ip,
                $userAgent,
                &$signaturePath
            ) {
                // Khóa hợp đồng để tránh hai yêu cầu ký đồng thời
                $contract = Contract::whereKey($signature->contract_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Khóa bản ghi chữ ký
                $signature = ContractSignature::whereKey($signature->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($signature->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'signature' => 'Chữ ký này đã được sử dụng hoặc không còn hiệu lực.',
                    ]);
                }

                if (
                    $signature->expires_at &&
                    $signature->expires_at->isPast()
                ) {
                    throw ValidationException::withMessages([
                        'signature' => 'Đường dẫn ký hợp đồng đã hết hạn.',
                    ]);
                }

                if ($contract->status !== 'waiting_signature') {
                    throw ValidationException::withMessages([
                        'contract' => 'Hợp đồng không ở trạng thái chờ ký.',
                    ]);
                }

                // Khóa phòng
                $room = Room::whereKey($contract->room_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($room->status !== 'available') {
                    throw ValidationException::withMessages([
                        'room' => 'Phòng hiện không khả dụng.',
                    ]);
                }

                // Lưu ảnh chữ ký vào vùng lưu trữ riêng tư
                $signaturePath = 'contracts/signatures/' .
                    $contract->id . '/' . Str::uuid() . '.png';

                $saved = Storage::disk('local')->put(
                    $signaturePath,
                    $imageData
                );

                if (!$saved) {
                    throw new \RuntimeException(
                        'Không thể lưu ảnh chữ ký.'
                    );
                }

                // Cập nhật bản ghi chữ ký
                $signature->update([
                    'signature_path' => $signaturePath,
                    'status' => 'signed',
                    'signed_at' => now(),
                    'signed_ip' => $ip,
                    'user_agent' => $userAgent,
                ]);

                // Kích hoạt hợp đồng
                // Người thuê đã ký, chờ chủ nhà ký
                $contract->update([
                    'status' => 'waiting_owner_signature',
                ]);

                return $contract->fresh([
                    'room',
                    'tenants',
                ]);
            });
        } catch (\Throwable $e) {
            // Nếu transaction thất bại thì xóa ảnh đã lưu
            if ($signaturePath) {
                Storage::disk('local')->delete($signaturePath);
            }

            throw $e;
        }
    }


    /**
     * Hoàn tất quy trình ký hợp đồng của chủ nhà.
     */
    public function completeOwnerSigning(
        Contract $contract,
        string $signatureData,
        string $ip,
        string $userAgent
    ): Contract {
        // Kiểm tra định dạng Data URL
        if (!preg_match(
            '/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/',
            $signatureData,
            $matches
        )) {
            throw ValidationException::withMessages([
                'signature' => 'Dữ liệu chữ ký không đúng định dạng PNG.',
            ]);
        }

        // Giải mã ảnh Base64
        $imageData = base64_decode($matches[1], true);

        if (
            $imageData === false ||
            strlen($imageData) === 0 ||
            strlen($imageData) > 2 * 1024 * 1024
        ) {
            throw ValidationException::withMessages([
                'signature' => 'Ảnh chữ ký không hợp lệ hoặc vượt quá 2MB.',
            ]);
        }

        // Kiểm tra đúng định dạng PNG
        $imageInfo = getimagesizefromstring($imageData);

        if (
            $imageInfo === false ||
            $imageInfo['mime'] !== 'image/png'
        ) {
            throw ValidationException::withMessages([
                'signature' => 'File chữ ký phải là ảnh PNG hợp lệ.',
            ]);
        }

        $disk = Storage::disk('local');

        $signaturePath = null;
        $pdfPath = 'contracts/signed/contract-' . $contract->id . '.pdf';

        try {
            return DB::transaction(function () use (
                $contract,
                $imageData,
                $ip,
                $userAgent,
                $disk,
                &$signaturePath
            ) {
                // Khóa hợp đồng để tránh hai lần ký đồng thời
                $lockedContract = Contract::whereKey($contract->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedContract->status !== 'waiting_owner_signature') {
                    throw ValidationException::withMessages([
                        'contract' => 'Hợp đồng không ở trạng thái chờ chủ nhà ký.',
                    ]);
                }

                if (
                    $lockedContract->owner_signature_path ||
                    $lockedContract->owner_signed_at
                ) {
                    throw ValidationException::withMessages([
                        'contract' => 'Chủ nhà đã ký hợp đồng này.',
                    ]);
                }

                // Lưu ảnh chữ ký chủ nhà
                $signaturePath = 'contracts/signatures/owner/'
                    . $lockedContract->id . '/'
                    . Str::uuid() . '.png';

                $saved = $disk->put($signaturePath, $imageData);

                if (!$saved) {
                    throw new \RuntimeException(
                        'Không thể lưu ảnh chữ ký chủ nhà.'
                    );
                }

                // Cập nhật thông tin chữ ký chủ nhà
                $lockedContract->update([
                    'owner_signature_path' => $signaturePath,
                    'owner_signed_at' => now(),
                    'owner_signed_ip' => $ip,
                    'owner_user_agent' => $userAgent,
                ]);

                // Kích hoạt hợp đồng và cập nhật trạng thái phòng
                $activatedContract = $this->activateContract(
                    $lockedContract
                );

                // Tạo PDF sau khi cả hai bên đã ký
                return $this->generateSignedPdf($activatedContract);
            });
        } catch (\Throwable $e) {
            // Xóa ảnh chữ ký nếu quá trình xử lý thất bại
            if ($signaturePath && $disk->exists($signaturePath)) {
                $disk->delete($signaturePath);
            }

            // Xóa PDF nếu quá trình xử lý thất bại
            if ($disk->exists($pdfPath)) {
                $disk->delete($pdfPath);
            }

            throw $e;
        }
    }


    /**
     * Tạo PDF hợp đồng sau khi cả hai bên đã ký.
     */
    public function generateSignedPdf(Contract $contract): Contract
    {
        $contract->load([
            'owner',
            'room',
            'tenants',
            'contractServices',
        ]);

        // Lấy người đại diện bên thuê
        $representative = $contract->tenants
            ->firstWhere('pivot.is_representative', true)
            ?? $contract->tenants->first();

        if (!$representative) {
            throw new \RuntimeException(
                'Không tìm thấy người đại diện hợp đồng.'
            );
        }

        $disk = Storage::disk('local');

        // Lấy chữ ký người thuê
        $tenantSignature = ContractSignature::where('contract_id', $contract->id)
            ->where('tenant_id', $representative->id)
            ->where('status', 'signed')
            ->first();

        if (!$tenantSignature || !$tenantSignature->signature_path) {
            throw new \RuntimeException(
                'Không tìm thấy chữ ký hợp lệ của người thuê.'
            );
        }

        if (!$disk->exists($tenantSignature->signature_path)) {
            throw new \RuntimeException(
                'File chữ ký người thuê không tồn tại.'
            );
        }

        // Lấy chữ ký chủ nhà
        if (
            !$contract->owner_signature_path ||
            !$contract->owner_signed_at
        ) {
            throw new \RuntimeException(
                'Chủ nhà chưa ký hợp đồng.'
            );
        }

        if (!$disk->exists($contract->owner_signature_path)) {
            throw new \RuntimeException(
                'File chữ ký chủ nhà không tồn tại.'
            );
        }

        // Chuyển ảnh chữ ký thành Base64 để nhúng vào PDF
        $tenantImageData = $disk->get(
            $tenantSignature->signature_path
        );

        $ownerImageData = $disk->get(
            $contract->owner_signature_path
        );

        $tenantSignatureImage = 'data:image/png;base64,'
            . base64_encode($tenantImageData);

        $ownerSignatureImage = 'data:image/png;base64,'
            . base64_encode($ownerImageData);

        $houseSetting = HouseSetting::first();

        // Tạo PDF có đầy đủ chữ ký hai bên
        $pdf = Pdf::loadView(
            'admin.contracts.pdf.contract',
            [
                'contract' => $contract,
                'houseSetting' => $houseSetting,
                'owner' => $contract->owner,
                'tenantSignatureImage' => $tenantSignatureImage,
                'ownerSignatureImage' => $ownerSignatureImage,
            ]
        );

        $pdf->setPaper('A4', 'portrait');

        $pdfPath = 'contracts/signed/contract-' . $contract->id . '.pdf';

        $saved = $disk->put($pdfPath, $pdf->output());

        if (!$saved) {
            throw new \RuntimeException(
                'Không thể lưu PDF hợp đồng đã ký.'
            );
        }

        $contract->update([
            'signed_pdf_path' => $pdfPath,
        ]);

        return $contract->fresh([
            'room',
            'tenants',
        ]);
    }
}
