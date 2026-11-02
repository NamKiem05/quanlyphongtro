@extends('admin.layouts.main')
@section('content')
<main class="main">

    <div class="filter-container">

        <div class="filter-header">
            <div>
                <h3 class="fw-bold mb-1">Quản lý người thuê phòng</h3>
                <p class="text-muted mb-0">
                    Quản lý danh sách tài khoản hệ thống
                </p>
            </div>
            <a href="#" class="btn-add-property">
                <i class="fa-solid fa-plus"></i>
                Thêm người
            </a>
        </div>

        <form action="{{ route('tenants.index') }}" method="GET" class="filter-form">

            <!-- Tìm kiếm -->
            <div class="filter-group search-group">
                <label>Tìm kiếm</label>
                <div class="input-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email..." value="{{ request('keyword') }}">
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="filter-group">
                <label>Vai trò</label>
                <select>
                    <option value="">Tất cả vai trò</option>
                    <option value="admin">Chủ nhà</option>
                    <option value="client">Người thuê</option>
                </select>
            </div>

            <!-- Sắp xếp -->
            <div class="filter-group">
                <label>Sắp xếp</label>
                <select>
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="name_asc">Tên A - Z</option>
                    <option value="name_desc">Tên Z - A</option>
                </select>
            </div>

            <!-- Nút -->
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-filter"></i>
                    Lọc
                </button>

                <button type="reset" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i>
                    Làm mới
                </button>
            </div>

        </form>
    </div>

    <div class="main-content">
        <table class="table">
            <thead>
                <tr>
                    <td>STT</td>
                    <td>Họ và tên</td>
                    <td>Email</td>
                    <td>Số điện thoại</td>
                    <td>Số CCCD</td>
                    <td>Vai trò</td>
                    <td>Ngày vào</td>
                    <td>Hành động</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($tenants as $tenant)
                <tr>
                    <td class="ps-4">
                        {{ $tenants->firstItem() + $loop->index }}
                    </td>

                    <td>
                        <div class="fw-semibold">
                            <b>{{ $tenant->full_name }}</b>
                        </div>
                    </td>

                    <td>
                        {{ $tenant->email }}
                    </td>
                    <td>
                        {{ $tenant->phone }}
                    </td>
                    <td>{{ $tenant->identity_number }}</td>
                    <td>
                        {{ $tenant->date_of_birth}}
                    </td>

                    <td>
                        {{ $tenant->created_at->format('d/m/Y') }}
                    </td>

                    <td class="action-buttons">
                        <a href="#" class="btn-edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Sửa
                        </a>
                        <a href="#" class="btn-show">
                            <i class="fa-solid fa-eye"></i>
                            Xem
                        </a>

                        <a href="#" class="btn-delete">
                            <i class="fa-solid fa-trash"></i>
                            Xóa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-building-circle-exclamation fs-1 text-muted mb-3"></i>
                        <h6 class="fw-semibold">Chưa có nhà trọ nào</h6>
                        <p class="text-muted mb-0">
                            Hãy thêm nhà trọ đầu tiên của bạn.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection