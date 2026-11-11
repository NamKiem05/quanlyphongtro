<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Validation\Rule;
use App\Mail\ContractSigningMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\HouseSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class ContractController extends Controller
{
    protected ContractService $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Danh sách hợp đồng
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
            'sort',
        ]);

        $contracts = $this->contractService->getContracts($filters);

        $statistics = $this->contractService->getStatistics();

        return view('admin.contracts.index', compact(
            'contracts',
            'statistics'
        ));
    }


    /**
     * Lấy dữ liệu phục vụ Modal tạo hợp đồng
     */
    public function createData()
    {
        $data = $this->contractService->getDataForCreate();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /**
     * Tạo hợp đồng mới
     */
    public function store(Request $request)
    {
        $tenantIds = $request->input('tenant_ids', []);

        $validated = $request->validate([
            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'rent_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'deposit_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'billing_day' => [
                'required',
                'integer',
                'between:1,31',
            ],

            'terms' => [
                'nullable',
                'string',
            ],

            'tenant_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'tenant_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:tenants,id',
            ],

            'representative_tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
                Rule::in(is_array($tenantIds) ? $tenantIds : []),
            ],
        ], [
            'room_id.required' => 'Vui lòng chọn phòng thuê.',
            'room_id.exists' => 'Phòng thuê không tồn tại.',

            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',

            'end_date.after_or_equal' =>
            'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

            'rent_price.required' => 'Vui lòng nhập giá thuê.',
            'deposit_amount.required' => 'Vui lòng nhập tiền đặt cọc.',

            'billing_day.between' => 'Ngày thanh toán phải từ 1 đến 31.',

            'tenant_ids.required' => 'Vui lòng chọn người thuê.',
            'tenant_ids.min' => 'Hợp đồng phải có ít nhất một người thuê.',

            'representative_tenant_id.required' =>
            'Vui lòng chọn người đại diện.',

            'representative_tenant_id.in' =>
            'Người đại diện phải nằm trong danh sách người thuê.',
        ]);

        // Gọi Service tạo hợp đồng
        $contract = $this->contractService->createContract($validated, Auth::id());

        // Trả kết quả cho AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo hợp đồng thành công!',
                'data' => [
                    'contract_id' => $contract->id,
                ],
            ]);
        }

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Tạo hợp đồng thành công!');
    }



    /**
     * Chi tiết hợp đồng
     */
    public function show(Contract $contract)
    {
        $contract = $this->contractService
            ->getContractDetail($contract);

        $contract->setAttribute(
            'pdf_url',
            $contract->pdf_path
                ? route('contracts.pdf', ['contract' => $contract->id])
                : null
        );

        $contract->setAttribute(
            'signed_pdf_url',
            $contract->status === 'active' && $contract->signed_pdf_path
                ? route('contracts.signed-pdf', ['contract' => $contract->id])
                : null
        );

        return response()->json([
            'success' => true,
            'data' => $contract,
        ]);
    }
    /**
     * Lấy dữ liệu hợp đồng để chỉnh sửa bằng Modal
     */
    public function editData(Contract $contract)
    {
        $contract = $this->contractService->getContractForEdit($contract);

        $tenants = Tenant::whereDoesntHave('contracts')
            ->orWhereHas('contracts', function ($query) use ($contract) {
                $query->where('contracts.id', $contract->id);
            })
            ->orderBy('full_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'contract' => $contract,
                'tenants' => $tenants,
            ],
        ]);
    }
    /**
     * Cập nhật hợp đồng
     */
    public function update(Request $request, Contract $contract)
    {
        $tenantIds = $request->input('tenant_ids', []);

        // Validate dữ liệu
        $validated = $request->validate([
            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'rent_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'deposit_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'billing_day' => [
                'required',
                'integer',
                'between:1,31',
            ],

            'terms' => [
                'nullable',
                'string',
            ],

            'tenant_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'tenant_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:tenants,id',
            ],

            'representative_tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
                Rule::in(is_array($tenantIds) ? $tenantIds : []),
            ],
        ], [
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'rent_price.required' => 'Vui lòng nhập giá thuê.',
            'deposit_amount.required' => 'Vui lòng nhập tiền đặt cọc.',
            'billing_day.between' => 'Ngày thanh toán phải từ 1 đến 31.',
            'tenant_ids.required' => 'Vui lòng chọn người thuê.',
            'tenant_ids.min' => 'Hợp đồng phải có ít nhất một người thuê.',
            'representative_tenant_id.required' => 'Vui lòng chọn người đại diện.',
            'representative_tenant_id.in' => 'Người đại diện phải nằm trong danh sách người thuê.',
        ]);

        // Gọi Service cập nhật hợp đồng
        $this->contractService->updateContract($contract, $validated);

        // Nếu request được gửi bằng AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hợp đồng thành công!',
            ]);
        }

        // Nếu gửi form thông thường
        return redirect()
            ->route('contracts.index')
            ->with('success', 'Cập nhật hợp đồng thành công!');
    }


    // Xóa hợp đồng
    public function destroy(Contract $contract)
    {
        $this->contractService->deleteContract($contract);

        return response()->json([
            'success' => true,
            'message' => 'Xóa hợp đồng thành công!',
        ]);
    }

    /**
     * Gửi hợp đồng chờ ký
     */

    public function sendForSignature(Contract $contract)
    {
        try {
            // 1. Tạo token và lưu thông tin chữ ký
            $result = $this->contractService->sendForSignature($contract);

            $contract = $result['contract'];
            $representative = $result['representative'];
            $token = $result['token'];

            // 2. Kiểm tra email người đại diện
            if (
                !$representative->email ||
                !filter_var($representative->email, FILTER_VALIDATE_EMAIL)
            ) {
                throw new \Exception('Email người đại diện không hợp lệ.');
            }

            // 3. Lấy đầy đủ dữ liệu
            $contract->load([
                'owner',
                'room',
                'tenants',
                'contractServices',
            ]);

            $houseSetting = HouseSetting::first();
            $owner = $contract->owner ?? Auth::user();

            // 4. Tạo PDF
            $pdf = Pdf::loadView(
                'admin.contracts.pdf.contract',
                [
                    'contract' => $contract,
                    'houseSetting' => $houseSetting,
                    'owner' => $owner,
                ]
            );

            $pdf->setPaper('A4', 'portrait');

            // 5. Lưu PDF
            $pdfPath = 'contracts/contract-' . $contract->id . '.pdf';

            $saved = Storage::disk('local')->put(
                $pdfPath,
                $pdf->output()
            );

            if (!$saved) {
                throw new \Exception('Không thể lưu file PDF hợp đồng.');
            }

            $contract->update([
                'pdf_path' => $pdfPath,
            ]);

            // 6. Tạo URL ký hợp đồng
            $signingUrl = route('contract-signing.show', [
                'token' => $token,
            ]);

            // 7. Gửi email
            Mail::to($representative->email)
                ->send(new ContractSigningMail(
                    $contract,
                    $representative,
                    $signingUrl
                ));

            // 8. Chuyển trạng thái sau khi gửi thành công
            $contract->update([
                'status' => 'waiting_signature',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi hợp đồng đến email người đại diện.',
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi hợp đồng. Vui lòng kiểm tra email hoặc thử lại.',
            ], 500);
        }
    }
    /**
     * Chủ nhà ký hợp đồng.
     */
    public function ownerSign(
        Request $request,
        Contract $contract,
        ContractService $contractService
    ) {
        // Kiểm tra quyền sở hữu hợp đồng
        if ((int) $contract->owner_id !== (int) Auth::id()) {
            abort(403, 'Anh/chị không có quyền ký hợp đồng này.');
        }

        // Kiểm tra dữ liệu chữ ký
        $validated = $request->validate([
            'signature' => [
                'required',
                'string',
                'max:3000000',
            ],
        ], [
            'signature.required' => 'Vui lòng ký tên trước khi xác nhận.',
            'signature.string' => 'Dữ liệu chữ ký không hợp lệ.',
            'signature.max' => 'Ảnh chữ ký vượt quá dung lượng cho phép.',
        ]);

        // Lưu chữ ký, kích hoạt hợp đồng và tạo PDF
        $updatedContract = $contractService->completeOwnerSigning(
            $contract,
            $validated['signature'],
            $request->ip(),
            substr($request->userAgent() ?? '', 0, 255)
        );

        return response()->json([
            'success' => true,
            'message' => 'Chủ nhà đã ký thành công. Hợp đồng đã được kích hoạt.',
            'data' => [
                'id' => $updatedContract->id,
                'status' => $updatedContract->status,
                'owner_signed_at' => $updatedContract->owner_signed_at,
                'signed_pdf_url' => route(
                    'contracts.signed-pdf',
                    $updatedContract->id
                ),
            ],
        ]);
    }



    /**
     * Xác nhận hợp đồng đã ký và kích hoạt
     */
    public function activate(Contract $contract)
    {
        $contract = $this->contractService
            ->activateContract($contract);

        return response()->json([
            'success' => true,
            'message' => 'Hợp đồng đã được kích hoạt thành công.',
            'data' => [
                'contract' => $contract,
            ],
        ]);
    }

    /**
     * Chấm dứt hợp đồng
     */
    public function terminate(Contract $contract)
    {
        $contract = $this->contractService
            ->terminateContract($contract);

        return response()->json([
            'success' => true,
            'message' => 'Hợp đồng đã được chấm dứt.',
            'data' => [
                'contract' => $contract,
            ],
        ]);
    }



    public function signedPdf(Contract $contract)
    {
        if (
            $contract->status !== 'active' ||
            !$contract->signed_pdf_path
        ) {
            abort(404, 'Không tìm thấy hợp đồng đã ký.');
        }

        $disk = Storage::disk('local');

        if (!$disk->exists($contract->signed_pdf_path)) {
            abort(404, 'File PDF hợp đồng đã ký không tồn tại.');
        }

        return response()->file(
            $disk->path($contract->signed_pdf_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="hop-dong-da-ky.pdf"',
            ]
        );
    }
}
