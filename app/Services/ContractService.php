<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
