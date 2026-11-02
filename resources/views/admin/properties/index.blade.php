@extends('admin.layouts.main')

@section('content')

<main class="main">

    <div class="filter-container">

        <div class="filter-header">
            <div>
                <h3 class="fw-bold mb-1">Quản lý nhà trọ</h3>
                <p class="text-muted mb-0">
                    Quản lý thông tin các nhà trọ của bạn
                </p>
            </div>

            <a href="#" class="btn-add-property">
                <i class="fa-solid fa-plus"></i>
                Thêm nhà trọ
            </a>
        </div>

        <form class="filter-form">

            <!-- Tìm kiếm -->
            <div class="filter-group search-group">
                <label>Tìm kiếm</label>
                <div class="input-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Tên, số điện thoại, email...">
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="filter-group">
                <label>Trạng thái</label>
                <select>
                    <option value="">Tất cả trạng thái</option>
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Ngừng hoạt động</option>
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
                    <td>Địa chỉ</td>
                    <td>Mô tả</td>
                    <td>Ngày</td>
                    <td>Hành động</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $property)
                <tr>
                    <td class="ps-4">
                        {{ $properties->firstItem() + $loop->index }}
                    </td>

                    <td>
                        <div class="fw-semibold">
                            {{ $property->name }}
                        </div>
                    </td>

                    <td>
                        {{ $property->address }}
                    </td>

                    <td>
                        {{ Str::limit($property->description, 50) }}
                    </td>

                    <td>
                        {{ $property->created_at->format('d/m/Y') }}
                    </td>

                    <td class="action-buttons">
                        <a href="#" class="btn-edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Sửa
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
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Hiển thị {{ $properties->firstItem() ?? 0 }}
                - {{ $properties->lastItem() ?? 0 }}
                trong tổng {{ $properties->total() }} nhà trọ
            </small>

            {{ $properties->links() }}
        </div>
    </div>
</main>

@endsection