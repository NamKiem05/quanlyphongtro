<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
}
