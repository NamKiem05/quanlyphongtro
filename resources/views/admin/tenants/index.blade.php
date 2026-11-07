@extends('admin.layouts.main')
@section('content')
@php
$genders = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'];
$sorts = [
'newest' => 'Mới nhất',
'oldest' => 'Cũ nhất',
'name_asc' => 'Tên A - Z',
'name_desc' => 'Tên Z - A',
];
$hasFilter = request()->filled('keyword') || request()->filled('gender') || request('sort', 'newest') !== 'newest';
@endphp


    {{-- ===== Bộ lọc ===== --}}
    <section class="filter-container">
        <div class="header-title">
            <div>
                <h1 class="page-title">Quản lý người thuê phòng</h1>
                <p class="page-subtitle">Quản lý danh sách người thuê phòng trong hệ thống</p>
            </div>
            <div class="page-stat">
                <i class="fa-solid fa-users"></i>
                <span>Tổng cộng <b>{{ $tenants->total() }}</b> người</span>
            </div>
        </div>
        <form action="{{ route('tenants.index') }}" method="GET" class="filter-form">

            <div class="filter-group search-group">
                <label for="keyword">Tìm kiếm</label>
                <div class="input-search">
                    <input type="text" id="keyword" name="keyword" class="form-control"
                        placeholder="Tìm theo tên hoặc email..." value="{{ request('keyword') }}">
                </div>
            </div>

            <div class="filter-group">
                <label for="gender">Giới tính</label>
                <select name="gender" id="gender" onchange="this.form.submit()">
                    <option value="">Tất cả giới tính</option>
                    @foreach ($genders as $value => $label)
                    <option value="{{ $value }}" @selected(request('gender')===$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="sort">Sắp xếp</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    @foreach ($sorts as $value => $label)
                    <option value="{{ $value }}" @selected(request('sort', 'newest' )===$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-filter"></i> Lọc
                </button>
                {{-- type="reset" không xóa được giá trị đang lọc, nên dùng link về trang gốc --}}
                <a href="{{ route('tenants.index') }}" class="btn-reset {{ $hasFilter ? '' : 'is-disabled' }}">
                    <i class="fa-solid fa-rotate-left"></i> Bỏ lọc
                </a>
            </div>
        </form>
    </section>

    {{-- ===== Danh sách ===== --}}
    <section class="main-content">

        <div class="box-btn">
            <button type="button" class="btn-add-property" id="btnAddTenant">
                <i class="fa-solid fa-plus"></i> Thêm người
            </button>
            <a href="{{ route('tenants.export') }}" class="btn-exe">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            {{-- TODO: thay "#" bằng route xuất PDF của bạn --}}
            <a href="#" class="btn-pdf">
                <i class="fa-solid fa-file-pdf"></i> Xuất PDF
            </a>
        </div>

        <div class="table-wrap">
            <table class="tenant-table">
                <thead>
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Số CCCD</th>
                        <th scope="col">Giới tính</th>
                        <th scope="col">Ngày vào</th>
                        <th scope="col" class="col-actions">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenants as $tenant)
                    @php
                    $genderKey = array_key_exists($tenant->gender, $genders) ? $tenant->gender : 'other';
                    @endphp
                    <tr>
                        <td class="col-index">{{ $tenants->firstItem() + $loop->index }}</td>

                        <td>
                            <div class="tenant-name">
                                <span class="avatar">{{ mb_strtoupper(mb_substr($tenant->full_name, 0, 1)) }}</span>
                                <b>{{ $tenant->full_name }}</b>
                            </div>
                        </td>

                        <td class="text-muted-cell">{{ $tenant->email }}</td>
                        <td>{{ $tenant->phone }}</td>
                        <td class="mono">{{ $tenant->identity_number }}</td>

                        <td>
                            <span class="badge badge-{{ $genderKey }}">{{ $genders[$genderKey] }}</span>
                        </td>

                        <td>{{ $tenant->created_at?->format('d/m/Y') }}</td>

                        <td class="action-buttons">
                            <button type="button" class="btn-show js-tenant-action" title="Xem chi tiết"
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
                                <i class="fa-solid fa-eye"></i> Xem
                            </button>

                            <button type="button" class="btn-edit js-tenant-action" title="Chỉnh sửa"
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
                                <i class="fa-solid fa-pen-to-square"></i> Sửa
                            </button>

                            <button type="button" class="btn-delete" title="Xóa"
                                data-url="{{ route('tenants.delete', $tenant->id) }}"
                                data-name="{{ $tenant->full_name }}">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-regular fa-folder-open"></i>
                                <p class="empty-title">Danh sách trống</p>
                                <p class="empty-desc">
                                    {{ $hasFilter ? 'Không tìm thấy kết quả phù hợp với bộ lọc.' : 'Chưa có người thuê nào. Hãy bấm "Thêm người" để bắt đầu.' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        
        <div class="table-footer">
            <span class="result-info">
                Hiển thị {{ $tenants->firstItem() }}–{{ $tenants->lastItem() }} / {{ $tenants->total() }} người
            </span>
            <div class="list-links-tenants">
            @if ($tenants->hasPages())
            {{ $tenants->withQueryString()->links('pagination::bootstrap-4') }}
            @endif
            </div>
        </div>
        
    </section>
</main>

@include('admin.tenants.partials.modal_tenants')
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