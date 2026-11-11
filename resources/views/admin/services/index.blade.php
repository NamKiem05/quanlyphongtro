@extends('admin.layouts.main')

@section('content')

<style>
    /* =========================================================
       Trang quản lý dịch vụ - tông xanh dương
       Mọi selector đều nằm trong .services-page để không ảnh
       hưởng tới các trang khác.
       ========================================================= */
    .services-page {
        --blue-900: #0b2a5b;
        --blue-700: #1d4ed8;
        --blue-600: #2563eb;
        --blue-500: #3b82f6;
        --blue-100: #dbeafe;
        --blue-50: #eff6ff;
        --sky-600: #0284c7;
        --sky-50: #f0f9ff;
        --ink: #0f172a;
        --muted: #64748b;
        --line: #e2e8f0;
        --surface: #ffffff;
        --danger: #dc2626;
        --danger-bg: #fef2f2;

        color: var(--ink);
        padding-top: 8px;
        padding-bottom: 32px;
    }

    .services-page .card {
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface);
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04), 0 4px 16px rgba(37, 99, 235, .05);
    }

    /* ---------- Tiêu đề trang ---------- */
    .services-page .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .services-page .page-header .header-icon {
        flex: 0 0 auto;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 22px;
        color: #fff;
        background: linear-gradient(135deg, var(--blue-600), var(--blue-900));
        box-shadow: 0 8px 18px rgba(37, 99, 235, .28);
    }

    .services-page .page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--blue-900);
        letter-spacing: -.01em;
    }

    .services-page .page-subtitle {
        margin: 2px 0 0;
        font-size: 14px;
        color: var(--muted);
    }

    /* ---------- Thẻ thống kê ---------- */
    /* =====================================================
   STATISTICS CARDS - QUẢN LÝ DỊCH VỤ
===================================================== */


    .services-page .stat-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 100px;
        padding: 20px 20px;
        margin-bottom: 10px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        transition: all 0.25s ease;
    }

    .services-page .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
    }

    /* Icon */
    .services-page .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        font-size: 22px;
        border-radius: 13px;
    }

    /* Nội dung */
    .services-page .stat-label {
        margin: 0 0 8px;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }

    .services-page .stat-value {
        margin: 0;
        color: #172554;
        font-size: 29px;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    /* Tổng số dịch vụ */
    .services-page .stat-total .stat-icon {
        color: #2563eb;
        background: #eff6ff;
    }

    .services-page .stat-total {
        border-left: 4px solid #3b82f6;
    }

    /* Dịch vụ tính theo chỉ số */
    .services-page .stat-available .stat-icon {
        color: #0891b2;
        background: #ecfeff;
    }

    .services-page .stat-available {
        border-left: 4px solid #06b6d4;
    }

    /* Dịch vụ cố định */
    .services-page .stat-occupied .stat-icon {
        color: #7c3aed;
        background: #f5f3ff;
    }

    .services-page .stat-occupied {
        border-left: 4px solid #8b5cf6;
    }

    /* Trang trí các thanh */
    .services-page .stat-bars {
        position: absolute;
        right: 20px;
        bottom: 0;
        display: flex;
        align-items: flex-end;
        gap: 5px;
        height: 45px;
        opacity: 0.13;
        pointer-events: none;
    }

    .services-page .stat-bars span {
        display: block;
        width: 7px;
        border-radius: 5px 5px 0 0;
    }

    .services-page .stat-bars span:nth-child(1) {
        height: 17px;
    }

    .services-page .stat-bars span:nth-child(2) {
        height: 27px;
    }

    .services-page .stat-bars span:nth-child(3) {
        height: 36px;
    }

    .services-page .stat-bars span:nth-child(4) {
        height: 23px;
    }

    .services-page .stat-bars span:nth-child(5) {
        height: 42px;
    }

    .services-page .stat-total .stat-bars span {
        background: #2563eb;
    }

    .services-page .stat-available .stat-bars span {
        background: #0891b2;
    }

    .services-page .stat-occupied .stat-bars span {
        background: #7c3aed;
    }

    /* 3 thẻ thống kê nằm ngang */
    .services-page .stat-grid {
        display: flex;
        flex-wrap: nowrap;
        gap: 16px;
    }

    .services-page .stat-grid>div {
        flex: 1 1 0;
        width: 33.333%;
        min-width: 0;
    }

    .services-page .stat-card {
        width: 100%;
        height: 125px;
    }

    /* Màn hình nhỏ: cho phép xuống hàng */
    @media (max-width: 767px) {
        .services-page .stat-grid {
            flex-wrap: wrap;
        }

        .services-page .stat-grid>div {
            flex: 1 1 100%;
            width: 100%;
        }
    }

    /* ---------- Bộ lọc ---------- */
    .services-page .filter-card {
        display: flex;
        flex-wrap: wrap;
        justify-content: left;
        align-items: center;
        gap: 12px;
        padding: 16px 18px;
    }

    .services-page .filter-form {
        flex: 1 1 560px;
        display: flex;
        flex-wrap: wrap;
        justify-content: left;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .services-page .field-box {
        position: relative;
        flex: 1 1 180px;
        min-width: 0;
    }

    .services-page .field-search {
        flex: 2 1 240px;
    }

    .services-page .field-box>i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        font-size: 13px;
        color: var(--blue-500);
        pointer-events: none;
    }

    .services-page .field-box .form-control,
    .services-page .field-box .form-select {
        height: 42px;
        width: 100%;
        padding-left: 38px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background-color: var(--blue-50);
        font-size: 14px;
        color: var(--ink);
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }

    .services-page .field-box .form-control:focus,
    .services-page .field-box .form-select:focus {
        background-color: #fff;
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .2);
        outline: 0;
    }

    .services-page .btn-search,
    .services-page .btn-add,
    .services-page .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 42px;
        padding: 0 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: background-color .15s, box-shadow .15s, color .15s, border-color .15s;
    }

    .services-page .btn-search {
        border: 1px solid var(--blue-600);
        background: var(--blue-600);
        color: #fff;
    }

    .services-page .btn-search:hover {
        background: var(--blue-700);
        border-color: var(--blue-700);
    }

    .services-page .btn-reset {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--muted);
    }

    .services-page .btn-reset:hover {
        color: var(--blue-700);
        border-color: var(--blue-100);
        background: var(--blue-50);
    }

    .services-page .btn-add {
        margin-left: auto;
        border: 1px solid transparent;
        color: #fff;
        background: linear-gradient(135deg, var(--blue-600), var(--blue-900));
        box-shadow: 0 6px 14px rgba(37, 99, 235, .28);
    }

    .services-page .btn-add:hover {
        box-shadow: 0 8px 18px rgba(37, 99, 235, .4);
    }

    .services-page .btn-search:focus-visible,
    .services-page .btn-add:focus-visible,
    .services-page .btn-reset:focus-visible,
    .services-page .btn-action:focus-visible {
        outline: 3px solid rgba(59, 130, 246, .45);
        outline-offset: 2px;
    }

    /* ---------- Bảng ---------- */
    .services-page .list-card {
        overflow: hidden;
    }

    .services-page .table {
        margin: 0;
        width: 100%;
    }

    .services-page .table thead th {
        padding: 14px 16px;
        border-bottom: 1px solid var(--blue-100);
        background: var(--blue-50);
        font-size: 13px;
        font-weight: 600;
        color: var(--blue-900);
        white-space: nowrap;
    }

    .services-page .table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        font-size: 14px;
        vertical-align: middle;
    }

    .services-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .services-page .table-hover tbody tr:hover>td {
        background-color: var(--sky-50);
        --bs-table-accent-bg: transparent;
    }

    .services-page .index-col {
        width: 64px;
        color: var(--muted);
        font-variant-numeric: tabular-nums;
    }

    .services-page .room-name {
        font-weight: 600;
        color: var(--blue-900);
    }

    .services-page .room-price {
        font-weight: 600;
        color: var(--blue-700);
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .services-page .unit-text {
        color: var(--muted);
    }

    .services-page .room-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--ink);
    }

    .services-page .room-count i {
        font-size: 12px;
        color: var(--blue-500);
    }

    /* Nhãn loại dịch vụ */
    .services-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .services-page .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .services-page .status-available {
        color: var(--sky-600);
        background: #e0f2fe;
    }

    .services-page .status-occupied {
        color: #4338ca;
        background: #e0e7ff;
    }

    /* Nút thao tác */
    .services-page .actions {
        display: flex;
        gap: 6px;
    }

    /* =========================================
   SERVICE ACTION DROPDOWN
========================================= */

    .services-page .service-action-dropdown {
        position: relative;
        display: inline-flex;
        justify-content: center;
    }

    /* Nút 3 chấm */
    .services-page .btn-action-menu {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        color: #64748b;
        font-size: 17px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .services-page .btn-action-menu:hover,
    .services-page .btn-action-menu.active {
        color: #838383;
        background: #d4d4d4;
        border-color: #bfdbfe;
    }

    /* Menu */
    .services-page .service-dropdown-menu {
        position: absolute;
        top: calc(100% + 7px);
        right: 0;
        z-index: 1000;
        display: none;
        min-width: 175px;
        padding: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        animation: serviceDropdownShow 0.18s ease;
    }

    .services-page .service-dropdown-menu.show {
        display: block;
    }

    @keyframes serviceDropdownShow {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Từng mục */
    .services-page .service-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 11px;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        text-align: left;
        background: transparent;
        border: none;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .services-page .service-dropdown-item i {
        width: 16px;
        color: #64748b;
        font-size: 14px;
    }

    .services-page .service-dropdown-item:hover {
        color: #2563eb;
        background: #eff6ff;
    }

    .services-page .service-dropdown-item:hover i {
        color: #2563eb;
    }

    /* Nút xóa */
    .services-page .service-dropdown-item.danger {
        color: #dc2626;
    }

    .services-page .service-dropdown-item.danger i {
        color: #dc2626;
    }

    .services-page .service-dropdown-item.danger:hover {
        background: #fef2f2;
    }

    /* Đường phân cách */
    .services-page .service-dropdown-divider {
        height: 1px;
        margin: 5px 3px;
        background: #e2e8f0;
    }

    /* Trạng thái rỗng */
    .services-page .empty-state {
        padding: 56px 16px !important;
        text-align: center;
        color: var(--muted);
    }

    .services-page .empty-state i {
        display: grid;
        place-items: center;
        width: 60px;
        height: 60px;
        margin: 0 auto 12px;
        border-radius: 50%;
        font-size: 22px;
        color: var(--blue-600);
        background: var(--blue-100);
    }

    .services-page .empty-state p {
        margin: 0;
        font-weight: 500;
    }

    .services-page .empty-state small {
        display: block;
        margin-top: 4px;
        font-size: 13px;
    }

    /* ---------- Chân bảng & phân trang ---------- */
    .services-page .list-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        border-top: 1px solid var(--line);
        background: #fafcff;
    }

    .services-page .list-info {
        font-size: 13.5px;
        color: var(--muted);
    }

    .services-page .list-info strong {
        color: var(--blue-900);
    }

    .services-page .list-links .pagination {
        margin: 0;
        gap: 4px;
    }

    .services-page .list-links .page-link {
        min-width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        padding: 0 10px;
        border: 1px solid var(--line);
        border-radius: 8px !important;
        font-size: 13.5px;
        color: var(--blue-700);
    }

    .services-page .list-links .page-link:hover {
        background: var(--blue-50);
        border-color: var(--blue-100);
    }

    .services-page .list-links .page-item.active .page-link {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: #fff;
    }

    .services-page .list-links .page-item.disabled .page-link {
        color: #94a3b8;
        background: #f8fafc;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 767.98px) {
        .services-page .page-title {
            font-size: 20px;
        }

        .services-page .stat-value {
            font-size: 24px;
        }

        .services-page .stat-bars {
            display: none;
        }

        .services-page .filter-form {
            flex-basis: 100%;
        }

        .services-page .field-box,
        .services-page .field-search {
            flex: 1 1 100%;
        }

        .services-page .btn-search,
        .services-page .btn-reset,
        .services-page .btn-add {
            flex: 1 1 100%;
            margin-left: 0;
        }
    }
</style>

<div class="container-fluid services-page">

    {{-- Tiêu đề --}}
    <div class="page-header">
        <div class="header-icon">
            <i class="fa-solid fa-gears"></i>
        </div>
        <div>
            <h4 class="page-title">Quản lý dịch vụ</h4>
            <p class="page-subtitle">
                Quản lý danh mục dịch vụ và đơn giá áp dụng cho phòng trọ
            </p>
        </div>
    </div>

    {{-- Thống kê --}}
    <div class="row g-3 mb-4 stat-grid">

        <div class="col-12 col-md-4">
            <div class="card stat-card stat-total">
                <div class="stat-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <p class="stat-label">Tổng số dịch vụ</p>
                    <h3 class="stat-value">{{ $statistics['total'] }}</h3>
                </div>
                <div class="stat-bars" aria-hidden="true">
                    <span></span><span></span><span></span>
                    <span></span><span></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card stat-available">
                <div class="stat-icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div>
                    <p class="stat-label">Dịch vụ tính theo chỉ số</p>
                    <h3 class="stat-value">{{ $statistics['metered'] }}</h3>
                </div>
                <div class="stat-bars" aria-hidden="true">
                    <span></span><span></span><span></span>
                    <span></span><span></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card stat-occupied">
                <div class="stat-icon">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <p class="stat-label">Dịch vụ cố định</p>
                    <h3 class="stat-value">{{ $statistics['fixed'] }}</h3>
                </div>
                <div class="stat-bars" aria-hidden="true">
                    <span></span><span></span><span></span>
                    <span></span><span></span>
                </div>
            </div>
        </div>

    </div>

    {{-- Tìm kiếm, lọc và thêm dịch vụ --}}
    <div class="card filter-card mb-4">

        <form class="filter-form"
            action="{{ route('services.index') }}"
            method="GET">

            {{-- Tìm kiếm --}}
            <div class="field-box field-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Tìm tên dịch vụ..."
                    aria-label="Tìm tên dịch vụ"
                    value="{{ request('search') }}">
            </div>

            {{-- Lọc loại dịch vụ --}}
            <div class="field-box field-status">
                <i class="fa-solid fa-filter"></i>
                <select name="type" class="form-select" aria-label="Lọc theo loại dịch vụ">
                    <option value="">Tất cả loại dịch vụ</option>
                    <option value="metered"
                        {{ request('type') == 'metered' ? 'selected' : '' }}>
                        Tính theo chỉ số
                    </option>
                    <option value="fixed"
                        {{ request('type') == 'fixed' ? 'selected' : '' }}>
                        Cố định
                    </option>
                </select>
            </div>

            {{-- Sắp xếp --}}
            <div class="field-box field-sort">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
                <select name="sort" class="form-select" aria-label="Sắp xếp">
                    <option value="newest"
                        {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                        Mới nhất
                    </option>
                    <option value="oldest"
                        {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                        Cũ nhất
                    </option>
                    <option value="price_asc"
                        {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                        Giá tăng dần
                    </option>
                    <option value="price_desc"
                        {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                        Giá giảm dần
                    </option>
                </select>
            </div>

            <button type="submit" class="btn-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                Tìm kiếm
            </button>

            {{-- Chỉ hiện khi đang có bộ lọc --}}
            @if (request()->filled('search') || request()->filled('type') || request()->filled('sort'))
            <a href="{{ route('services.index') }}" class="btn-reset">
                <i class="fa-solid fa-rotate-left"></i>
                Đặt lại
            </a>
            @endif

        </form>

        <button type="button" class="btn-add" id="openAddServiceModal">
            <i class="fa-solid fa-plus"></i>
            Thêm dịch vụ
        </button>

    </div>

    {{-- Danh sách dịch vụ --}}
    <div class="card list-card">

        <div class="table-wrap">
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên dịch vụ</th>
                            <th>Loại dịch vụ</th>
                            <th>Đơn vị tính</th>
                            <th>Đơn giá</th>
                            <th>Số phòng áp dụng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($services as $service)

                        <tr>
                            <td class="index-col">
                                {{ $services->firstItem() + $loop->index }}
                            </td>

                            <td class="room-name">
                                {{ $service->name }}
                            </td>

                            <td>
                                @if ($service->type === 'metered')
                                <span class="status-badge status-available">
                                    Tính theo chỉ số
                                </span>
                                @else
                                <span class="status-badge status-occupied">
                                    Cố định
                                </span>
                                @endif
                            </td>

                            <td class="unit-text">
                                {{ $service->unit ?: '—' }}
                            </td>

                            <td class="room-price">
                                {{ number_format($service->unit_price, 0, ',', '.') }} đ
                            </td>

                            <td>
                                <span class="room-count">
                                    <i class="fa-solid fa-door-open"></i>
                                    {{ $service->room_services_count }} phòng
                                </span>
                            </td>

                            <td>
                                <div class="service-action-dropdown">

                                    {{-- Nút 3 chấm --}}
                                    <button
                                        type="button"
                                        class="btn-action-menu"
                                        title="Thao tác"
                                        aria-label="Thao tác dịch vụ"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    {{-- Menu thao tác --}}
                                    <div class="service-dropdown-menu">

                                        <button
                                            type="button"
                                            class="service-dropdown-item btn-service-detail"
                                            data-url="{{ route('services.detail', $service->id) }}">
                                            <i class="fa-solid fa-eye"></i>
                                            <span>Xem chi tiết</span>
                                        </button>

                                        <button
                                            type="button"
                                            class="service-dropdown-item btn-edit-service"
                                            data-service-id="{{ $service->id }}"
                                            data-detail-url="{{ route('services.detail', $service->id) }}"
                                            data-update-url="{{ route('services.update', $service->id) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>Chỉnh sửa</span>
                                        </button>

                                        <div class="service-dropdown-divider"></div>

                                        <button
                                            type="button"
                                            class="service-dropdown-item danger btn-delete-service"
                                            data-url="{{ route('services.destroy', $service->id) }}"
                                            data-name="{{ $service->name }}">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>Xóa dịch vụ</span>
                                        </button>

                                    </div>
                                </div>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <p>Không tìm thấy dịch vụ nào.</p>
                                <small>Thử đổi từ khóa hoặc bỏ bớt bộ lọc.</small>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        {{-- Chân bảng --}}
        <div class="list-footer">

            <div class="list-info">
                @if ($services->total() > 0)
                Hiển thị <strong>{{ $services->firstItem() }} - {{ $services->lastItem() }}</strong>
                trong tổng số <strong>{{ $services->total() }}</strong> dịch vụ
                @else
                Hiển thị 0 dịch vụ
                @endif
            </div>

            <div class="list-links">
                @if ($services->hasPages())
                {{ $services->withQueryString()->links('pagination::bootstrap-4') }}
                @endif
            </div>

        </div>

    </div>

</div>

@include('admin.services.partials.create-modal')
<script>
    // =========================================
// DROPDOWN THAO TÁC DỊCH VỤ
// =========================================

document.addEventListener("click", function (event) {
    const clickedButton = event.target.closest(".btn-action-menu");

    // Đóng các menu khác
    document.querySelectorAll(".service-dropdown-menu.show").forEach(menu => {
        if (!clickedButton || !menu.parentElement.contains(clickedButton)) {
            menu.classList.remove("show");

            const button = menu.parentElement.querySelector(".btn-action-menu");
            button?.classList.remove("active");
            button?.setAttribute("aria-expanded", "false");
        }
    });

    // Nếu nhấn vào nút 3 chấm
    if (clickedButton) {
        const dropdown = clickedButton
            .closest(".service-action-dropdown")
            .querySelector(".service-dropdown-menu");

        const isOpen = dropdown.classList.toggle("show");

        clickedButton.classList.toggle("active", isOpen);
        clickedButton.setAttribute("aria-expanded", String(isOpen));
    }
});
</script>
@endsection