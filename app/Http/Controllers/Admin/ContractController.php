<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Validation\Rule;

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
     * Chi tiết hợp đồng
     */
    public function show(Contract $contract)
    {
        $contract = $this->contractService
            ->getContractDetail($contract);

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
}
