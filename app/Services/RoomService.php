<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

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
        ];
    }

    // 3. Thêm phòng
    public function createRoom(array $data): Room
    {
        return Room::create($data);
    }

    // 4. Cập nhật phòng
    public function updateRoom(Room $room, array $data): Room
    {
        $room->update($data);

        return $room->refresh();
    }

    // 5. Xóa phòng
    public function deleteRoom(Room $room): bool
    {
        // Không cho xóa phòng đã có hợp đồng
        if ($room->contracts()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Không thể xóa phòng vì đã tồn tại hợp đồng liên quan.'
            ]);
        }

        return $room->delete();
    }

    // 6. Chi tiết phòng
    public function getRoomDetail(Room $room): Room
    {
        return $room->load([
            'contracts.tenants'
        ]);
    }
}
