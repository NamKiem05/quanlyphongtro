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
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone','like', "%{$keyword}%" );
            });
        }

        // Lấy danh sách người dùng, mỗi trang 10 người
        $tenants = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.tenants.index', compact('tenants'));
    }
}
