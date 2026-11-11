<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    protected ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    // 1. Danh sách dịch vụ
    public function index(Request $request)
    {
        $services = $this->serviceService->getServices(
            $request->only([
                'search',
                'type',
                'sort',
            ])
        );

        $statistics = $this->serviceService->getStatistics();

        return view('admin.services.index', compact(
            'services',
            'statistics'
        ));
    }

    // 2. Chi tiết dịch vụ
    public function detail(Service $service): JsonResponse
    {
        $service = $this->serviceService->getServiceDetail($service);

        return response()->json([
            'success' => true,
            'data' => $service,
        ]);
    }

    // 3. Thêm dịch vụ
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:metered,fixed'],
            'unit' => ['nullable', 'string', 'max:30'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên dịch vụ.',
            'name.max' => 'Tên dịch vụ không được vượt quá 100 ký tự.',
            'type.required' => 'Vui lòng chọn loại dịch vụ.',
            'type.in' => 'Loại dịch vụ không hợp lệ.',
            'unit.max' => 'Đơn vị tính không được vượt quá 30 ký tự.',
            'unit_price.required' => 'Vui lòng nhập đơn giá.',
            'unit_price.numeric' => 'Đơn giá phải là số.',
            'unit_price.min' => 'Đơn giá không được âm.',
        ]);

        $service = $this->serviceService->createService($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thêm dịch vụ thành công!',
            'data' => $service,
        ], 201);
    }

    // 4. Cập nhật dịch vụ
    public function update(Request $request, Service $service): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:metered,fixed'],
            'unit' => ['nullable', 'string', 'max:30'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên dịch vụ.',
            'type.required' => 'Vui lòng chọn loại dịch vụ.',
            'type.in' => 'Loại dịch vụ không hợp lệ.',
            'unit_price.required' => 'Vui lòng nhập đơn giá.',
            'unit_price.numeric' => 'Đơn giá phải là số.',
            'unit_price.min' => 'Đơn giá không được âm.',
        ]);

        $updatedService = $this->serviceService->updateService(
            $service,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dịch vụ thành công!',
            'data' => $updatedService,
        ]);
    }

    // 5. Xóa dịch vụ
    public function delete(Service $service): JsonResponse
    {
        try {
            $this->serviceService->deleteService($service);

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
