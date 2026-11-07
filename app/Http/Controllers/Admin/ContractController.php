<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;

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
}
