<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoomService;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Http\JsonResponse;



class RoomController extends Controller
{
    protected RoomService $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        // Lấy danh sách phòng
        $rooms = $this->roomService->getRooms(
            $request->only([
                'search',
                'status',
                'floor',
                'sort'
            ])
        );

        // Lấy thống kê phòng
        $statistics = $this->roomService->getStatistics();

        return view('admin.rooms.index', compact(
            'rooms',
            'statistics'
        ));
    }


    public function detail(Room $room): JsonResponse
    {
        $room = $this->roomService->getRoomDetail($room);

        return response()->json([
            'success' => true,
            'data' => $room
        ]);
    }

    // thêm phòng

    public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'floor' => ['nullable', 'integer', 'min:1'],
        'area' => ['nullable', 'numeric', 'min:0'],
        'rent_price' => ['required', 'numeric', 'min:0'],
        'status' => ['required', 'in:available,occupied,maintenance'],
        'description' => ['nullable', 'string', 'max:1000'],
    ], [
        'name.required' => 'Vui lòng nhập tên phòng.',
        'name.max' => 'Tên phòng không được vượt quá 255 ký tự.',
        'floor.integer' => 'Tầng phải là số nguyên.',
        'floor.min' => 'Tầng phải lớn hơn hoặc bằng 1.',
        'area.numeric' => 'Diện tích phải là số.',
        'area.min' => 'Diện tích không được âm.',
        'rent_price.required' => 'Vui lòng nhập giá thuê.',
        'rent_price.numeric' => 'Giá thuê phải là số.',
        'rent_price.min' => 'Giá thuê không được âm.',
        'status.required' => 'Vui lòng chọn trạng thái phòng.',
        'status.in' => 'Trạng thái phòng không hợp lệ.',
    ]);

    $room = $this->roomService->createRoom($validated);

    return response()->json([
        'success' => true,
        'message' => 'Thêm phòng thành công!',
        'data' => $room,
    ], 201);
}
}
