<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class RoomService
{
    // 1. Danh sách phòng: tìm kiếm, lọc, sắp xếp
    public function getRooms(array $filters = []): LengthAwarePaginator
    {
        $query = Room::query()->withCount('contracts');

        // Tìm kiếm theo tên phòng
        if (!empty($filters['search'])) {
            $query->where(
                'name',
                'like',
                '%' . $filters['search'] . '%'
            );
        }

        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Lọc theo tầng
        if (!empty($filters['floor'])) {
            $query->where('floor', $filters['floor']);
        }

        // Sắp xếp
        switch ($filters['sort'] ?? 'newest') {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'price_asc':
                $query->orderBy('rent_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('rent_price', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(10)->withQueryString();
    }

    // 2. Thống kê phòng
    public function getStatistics(): array
    {
        return [
            'total' => Room::count(),

            'available' => Room::where(
                'status',
                'available'
            )->count(),

            'occupied' => Room::where(
                'status',
                'occupied'
            )->count(),

            'maintenance' => Room::where(
                'status',
                'maintenance'
            )->count(),
            'inactive' => Room::where(
                'status',
                'inactive'
            )->count(),
        ];
    }

    // 3. Thêm phòng
    public function createRoom(array $data): Room
    {
        return DB::transaction(function () use ($data) {

            // Tách dữ liệu dịch vụ khỏi dữ liệu phòng
            $serviceIds = $data['services'] ?? [];
            $servicePrices = $data['service_prices'] ?? [];

            unset($data['services'], $data['service_prices']);

            // Giữ nguyên logic trạng thái phòng hiện tại
            $data['status'] = 'available';

            // Tạo phòng
            $room = Room::create($data);

            // Lưu các dịch vụ được chọn
            foreach ($serviceIds as $serviceId) {

                $service = \App\Models\Service::findOrFail($serviceId);

                $room->roomServices()->create([
                    'service_id' => $serviceId,
                    'quantity' => 1,
                    'unit_price' => $servicePrices[$serviceId]
                        ?? $service->unit_price,
                ]);
            }

            return $room;
        });
    }

    // 4. Cập nhật phòng

    // 4. Cập nhật phòng

    public function updateRoom(Room $room, array $data): Room
    {
        return DB::transaction(function () use ($room, $data) {

            // Tách dữ liệu dịch vụ khỏi dữ liệu phòng
            $serviceIds = $data['services'] ?? [];
            $servicePrices = $data['service_prices'] ?? [];

            unset($data['services'], $data['service_prices']);

            // Kiểm tra hợp đồng hiệu lực
            if (isset($data['status'])) {

                $hasActiveContract = $room->contracts()
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', today())
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', today());
                    })
                    ->exists();

                if ($data['status'] === 'occupied' && !$hasActiveContract) {
                    throw ValidationException::withMessages([
                        'status' => 'Không thể chuyển sang đang thuê khi phòng chưa có hợp đồng hiệu lực.'
                    ]);
                }

                if ($data['status'] === 'available' && $hasActiveContract) {
                    throw ValidationException::withMessages([
                        'status' => 'Phòng vẫn đang có hợp đồng hiệu lực, không thể chuyển về trạng thái trống.'
                    ]);
                }

                if ($data['status'] === 'inactive' && $hasActiveContract) {
                    throw ValidationException::withMessages([
                        'status' => 'Không thể ngừng sử dụng phòng khi vẫn còn hợp đồng hiệu lực.'
                    ]);
                }
            }

            // Cập nhật thông tin phòng
            $room->update($data);

            // Xóa các dịch vụ đã bỏ chọn
            $room->roomServices()
                ->when(
                    !empty($serviceIds),
                    fn($query) => $query->whereNotIn('service_id', $serviceIds)
                )
                ->when(
                    empty($serviceIds),
                    fn($query) => $query
                )
                ->delete();

            // Đồng bộ các dịch vụ được chọn
            foreach ($serviceIds as $serviceId) {

                $service = \App\Models\Service::findOrFail($serviceId);

                $room->roomServices()->updateOrCreate(
                    [
                        'service_id' => $serviceId,
                    ],
                    [
                        'quantity' => 1,
                        'unit_price' => $servicePrices[$serviceId]
                            ?? $service->unit_price,
                    ]
                );
            }

            return $room->refresh();
        });
    }
    // 5. Xóa phòng
    public function deleteRoom(Room $room): bool
    {
        // Kiểm tra lịch sử hợp đồng
        if ($room->contracts()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng đã có lịch sử hợp đồng.'
            ]);
        }

        // Kiểm tra yêu cầu bảo trì
        if ($room->maintenanceRequests()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng đã có lịch sử yêu cầu bảo trì.'
            ]);
        }

        // Kiểm tra dịch vụ phòng
        if ($room->roomServices()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng đã được thiết lập dịch vụ.'
            ]);
        }

        // Kiểm tra chỉ số điện nước
        if ($room->meterReadings()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng đã có lịch sử chỉ số điện nước.'
            ]);
        }

        // Kiểm tra chi phí
        if ($room->expenses()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng đã có lịch sử chi phí.'
            ]);
        }

        return $room->delete();
    }

    // 6. Chi tiết phòng
    public function getRoomDetail(Room $room): Room
    {
        return $room->load([
            'contracts.tenants',
            'roomServices.service',
        ]);
    }
}
