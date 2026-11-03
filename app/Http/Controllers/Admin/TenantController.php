<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();

        // Tìm kiếm theo tên hoặc email
        // Lọc theo giới tính
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        // Lấy danh sách người dùng, mỗi trang 10 người
        $tenants = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.tenants.index', compact('tenants'));
    }
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',

            'identity_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',

            'gender' => 'nullable|in:male,female,other',
            'permanent_address' => 'nullable|string|max:2000',

            'identity_front' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'identity_back' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'note' => 'nullable|string|max:5000',
        ]);

        // 2. Upload ảnh CCCD
        if ($request->hasFile('identity_front')) {
            $validated['identity_front'] =
                $request->file('identity_front')->store('tenants/identity', 'public');
        }

        if ($request->hasFile('identity_back')) {
            $validated['identity_back'] =
                $request->file('identity_back')->store('tenants/identity', 'public');
        }

        // 3. Lưu người thuê vào database
        Tenant::create($validated);

        // 4. Quay lại danh sách
        return redirect()
            ->route('tenants.index')
            ->with('success', 'Thêm người thuê thành công!');
    }


    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'identity_number' => 'nullable|string|max:20',
            'identity_front' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'identity_back' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'permanent_address' => 'nullable|string|max:2000',
            'note' => 'nullable|string|max:5000',
        ]);

        $tenant->update($validated);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Cập nhật người thuê thành công!');
    }
}
