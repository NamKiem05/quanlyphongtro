<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ServiceService
{
    /**
     * 1. Danh sách dịch vụ: tìm kiếm, lọc, sắp xếp
     */
    public function getServices(array $filters = []): LengthAwarePaginator
    {
        $query = Service::query()
            ->withCount('roomServices');

        // Tìm kiếm theo tên dịch vụ
        if (!empty($filters['search'])) {
            $query->where(
                'name',
                'like',
                '%' . $filters['search'] . '%'
            );
        }

        // Lọc theo loại dịch vụ
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Sắp xếp
        switch ($filters['sort'] ?? 'newest') {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'price_asc':
                $query->orderBy('unit_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('unit_price', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(10)->withQueryString();
    }

    /**
     * 2. Thống kê dịch vụ
     */
    public function getStatistics(): array
    {
        return [
            'total' => Service::count(),

            'metered' => Service::where(
                'type',
                'metered'
            )->count(),

            'fixed' => Service::where(
                'type',
                'fixed'
            )->count(),
        ];
    }

    /**
     * 3. Thêm dịch vụ
     */
    public function createService(array $data): Service
    {
        return Service::create($data);
    }

    /**
     * 4. Cập nhật dịch vụ
     */
    public function updateService(Service $service, array $data): Service
    {
        $service->update($data);

        return $service->refresh();
    }

    /**
     * 5. Xóa dịch vụ
     */
    public function deleteService(Service $service): bool
    {
        // Không cho xóa dịch vụ đã được sử dụng ở phòng
        if ($service->roomServices()->exists()) {
            throw ValidationException::withMessages([
                'service' => 'Không thể xóa dịch vụ đã được áp dụng cho phòng.'
            ]);
        }

        // Không cho xóa dịch vụ đã được lưu trong hợp đồng
        if ($service->contractServices()->exists()) {
            throw ValidationException::withMessages([
                'service' => 'Không thể xóa dịch vụ đã có trong hợp đồng.'
            ]);
        }

        return $service->delete();
    }

    /**
     * 6. Chi tiết dịch vụ
     */
    public function getServiceDetail(Service $service): Service
    {
        return $service->load([
            'roomServices.room',
            'contractServices.contract',
        ]);
    }
}
