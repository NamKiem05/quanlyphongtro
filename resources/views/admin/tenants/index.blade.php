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
                <label>Giới tính</label>

                <select name="gender" onchange="this.form.submit()">
                    <option value="">Tất cả giới tính</option>

                    <option value="male"
                        {{ request('gender') == 'male' ? 'selected' : '' }}>
                        Nam
                    </option>

                    <option value="female"
                        {{ request('gender') == 'female' ? 'selected' : '' }}>
                        Nữ
                    </option>

                    <option value="other"
                        {{ request('gender') == 'other' ? 'selected' : '' }}>
                        Khác
                    </option>
                </select>
            </div>

            <!-- Sắp xếp -->
            <div class="filter-group">
                <label for="sort">Sắp xếp</label>

                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="newest"
                        {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                        Mới nhất
                    </option>

                    <option value="oldest"
                        {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                        Cũ nhất
                    </option>

                    <option value="name_asc"
                        {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                        Tên A - Z
                    </option>

                    <option value="name_desc"
                        {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                        Tên Z - A
                    </option>
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
                    Bỏ lọc
                </button>
            </div>

        </form>
    </div>

    <div class="main-content">
        <div class="box-btn">
            <button type="button"
                class="btn-add-property"
                id="btnAddTenant">
                <i class="fa-solid fa-plus"></i>
                Thêm người
            </button>
            <a href="{{ route('tenants.export') }}" class="btn-exe">
                <i class="fa-solid fa-file-excel"></i>
                Excel
            </a>
            <a href="#" class="btn-pdf">
                <i class="fa-solid fa-file-pdf"></i>
                Xuất PDF
            </a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <td>STT</td>
                    <td>Họ và tên</td>
                    <td>Email</td>
                    <td>Số điện thoại</td>
                    <td>Số CCCD</td>
                    <td>Giới tính</td>
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
                        {{ $tenant->gender === "male" ? "Nam" : "Nữ"}}
                    </td>

                    <td>
                        {{ $tenant->created_at->format('d/m/Y') }}
                    </td>

                    <td class="action-buttons">
                        <button type="button"
                            class="btn-show js-tenant-action"
                            data-mode="view"
                            data-name="{{ $tenant->full_name }}"
                            data-phone="{{ $tenant->phone }}"
                            data-email="{{ $tenant->email }}"
                            data-identity="{{ $tenant->identity_number }}"
                            data-birth="{{ $tenant->date_of_birth }}"
                            data-front="{{ $tenant->identity_front ? asset('storage/' . $tenant->identity_front) : '' }}"
                            data-back="{{ $tenant->identity_back ? asset('storage/' . $tenant->identity_back) : '' }}"
                            data-gender="{{ $tenant->gender }}"
                            data-address="{{ $tenant->permanent_address }}"
                            data-note="{{ $tenant->note }}">
                            <i class="fa-solid fa-eye"></i>
                            Xem
                        </button>

                        <button type="button"
                            class="btn-edit js-tenant-action"
                            data-mode="edit"
                            data-name="{{ $tenant->full_name }}"
                            data-phone="{{ $tenant->phone }}"
                            data-email="{{ $tenant->email }}"
                            data-identity="{{ $tenant->identity_number }}"
                            data-birth="{{ $tenant->date_of_birth }}"
                            data-front="{{ $tenant->identity_front ? asset('storage/' . $tenant->identity_front) : '' }}"
                            data-back="{{ $tenant->identity_back ? asset('storage/' . $tenant->identity_back) : '' }}"
                            data-gender="{{ $tenant->gender }}"
                            data-address="{{ $tenant->permanent_address }}"
                            data-note="{{ $tenant->note }}"
                            data-update-url="{{ route('tenants.update', $tenant->id) }}">
                            <i class="fa-solid fa-edit"></i>
                            Sửa
                        </button>
                        <button type="button"
                            class="btn-delete"
                            data-url="{{ route('tenants.delete', $tenant->id) }}"
                            data-name="{{ $tenant->full_name }}">

                            <i class="fa-solid fa-trash"></i>
                            Xóa

                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" height="50px">
                        Danh sách trống
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

<!-- form modal -->
<div class="modal-overlay" id="tenantModal">
    <div class="box-modal">
        <div class="modal-header">
            <div>
                <h3 id="modalTitle">Thêm người thuê</h3>
                <p>Nhập thông tin người thuê mới</p>
            </div>
        </div>

        <form id="tenantForm"
            action="{{ route('tenants.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-group">
                <label>Họ và tên <span>*</span></label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" placeholder="Nhập họ và tên" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Số điện thoại <span>*</span></label>
                    <input type="text" name="phone" id="phone" placeholder="Nhập số điện thoại">
                </div>

                <div class="form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Số CCCD <span>*</span></label>
                    <input type="text" name="identity_number" id="identity_number" placeholder="Nhập số CCCD">
                </div>

                <div class="form-group">
                    <label>Ngày sinh <span>*</span></label>
                    <input type="date" name="date_of_birth" id="date_of_birth">
                </div>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gender" id="gender">
                    <option value="">Chọn giới tính <span>*</span></option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ảnh căn cước công dân <span>*</span></label>

                <div class="cccd-upload">
                    <div class="upload-item">
                        <label for="identity_front" class="upload-box">
                            <i class="fa-solid fa-plus"></i>
                            <img id="previewFront" src="" alt="">
                        </label>
                        <input type="file" name="identity_front"
                            id="identity_front" accept="image/*" hidden>
                        <span>Mặt trước</span>
                    </div>

                    <div class="upload-item">
                        <label for="identity_back" class="upload-box">
                            <i class="fa-solid fa-plus"></i>
                            <img id="previewBack" src="" alt="">
                        </label>
                        <input type="file" name="identity_back"
                            id="identity_back" accept="image/*" hidden>
                        <span>Mặt sau</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Địa chỉ thường trú <span>*</span></label>
                <input type="text" name="permanent_address"
                    id="permanent_address" placeholder="Xã, phường, tỉnh,.....">
            </div>

            <div class="form-group">
                <label>Ghi chú <span>*</span></label>
                <textarea name="note" id="note" placeholder="Nhập ghi chú"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" id="cancelModal" class="btn-close-modal">
                    Hủy
                </button>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fa-solid fa-download"></i>
                    Lưu
                </button>
            </div>

        </form>
    </div>

</div>
<!-- Modal xác nhận xóa -->
<div class="modal-delete" id="deleteModal">

    <div class="modal-delete-content">

        <div class="delete-icon">
            <i class="fa-solid fa-trash-can"></i>
        </div>

        <h3>Xác nhận xóa người thuê</h3>

        <p>
            <strong id="tenantName"></strong>?
        </p>

        <span class="delete-warning">
            Hành động này không thể hoàn tác!
        </span>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-delete-actions">
                <button type="button" id="btnCancel">
                    Hủy
                </button>

                <button type="submit" class="btn-confirm-delete">
                    <i class="fa-solid fa-trash"></i>
                    Xóa
                </button>
            </div>
        </form>

    </div>
</div>
@endsection