@extends('admin.layouts.main')

@section('content')

@php
// Khai báo 1 lần, không lặp lại trong vòng foreach
$statusLabels = [
'draft' => 'Bản nháp',
'waiting_signature' => 'Chờ khách ký',
'waiting_owner_signature'=> 'Chờ chủ nhà ký',
'active' => 'Đang hiệu lực',
'expired' => 'Đã hết hạn',
'terminated' => 'Đã chấm dứt',
];

$statCards = [
    ['key' => '', 'label' => 'Tổng hợp đồng', 'value' => $statistics['total']],
    ['key' => 'draft', 'label' => 'Bản nháp', 'value' => $statistics['draft']],
    ['key' => 'waiting_signature', 'label' => 'Chờ người thuê ký', 'value' => $statistics['waiting_signature']],
    ['key' => 'waiting_owner_signature', 'label' => 'Chờ chủ nhà ký', 'value' => $statistics['waiting_owner_signature']],
    ['key' => 'active', 'label' => 'Đang hiệu lực', 'value' => $statistics['active']],
    ['key' => 'expired', 'label' => 'Đã hết hạn', 'value' => $statistics['expired']],
    ['key' => 'terminated', 'label' => 'Đã chấm dứt', 'value' => $statistics['terminated']],
];

$currentStatus = (string) request('status', '');
$hasFilter = request()->filled('search')
|| request()->filled('status')
|| request('sort', 'newest') !== 'newest';
@endphp

<div class="container-fluid contracts-page">
    <div>
        {{-- Tiêu đề --}}
        <header class="page-head">
            <div>
                <h1 class="page-title">Quản lý hợp đồng</h1>
                <p class="page-subtitle">Theo dõi và quản lý hợp đồng thuê phòng</p>
            </div>

            <button type="button" class="btn btn-brand" id="btnCreateContract" data-open-create>
                <i class="fa-solid fa-plus"></i>
                Tạo hợp đồng
            </button>
        </header>
        {{-- Thống kê: bấm vào để lọc nhanh theo trạng thái --}}
        <nav class="stat-strip" aria-label="Thống kê theo trạng thái">
            @foreach ($statCards as $card)
            @php
            $isActive = $currentStatus === $card['key'];
            $statUrl = route('contracts.index', array_filter(
            array_merge(request()->except(['page', 'status']), ['status' => $card['key']])
            ));
            @endphp

            <a href="{{ $statUrl }}"
                class="stat-item {{ $card['key'] ? 'tone-' . $card['key'] : 'tone-all' }} {{ $isActive ? 'is-active' : '' }}"
                @if ($isActive) aria-current="true" @endif>
                <span class="stat-label">
                    <i class="stat-dot"></i>{{ $card['label'] }}
                </span>
                <span class="stat-value">{{ number_format($card['value'], 0, ',', '.') }}</span>
            </a>
            @endforeach
        </nav>
    </div>
    {{-- Tìm kiếm và bộ lọc --}}
    <section class="panel filter-panel">
        <form action="{{ route('contracts.index') }}" method="GET" class="filter-form">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Tìm theo tên phòng hoặc người thuê..."
                    aria-label="Tìm kiếm hợp đồng"
                    value="{{ request('search') }}">
            </div>

            <select name="status" class="form-select" aria-label="Lọc theo trạng thái" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                @foreach ($statusLabels as $value => $label)
                <option value="{{ $value }}" @selected(request('status')===$value)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="sort" class="form-select" aria-label="Sắp xếp" onchange="this.form.submit()">
                <option value="newest" @selected(request('sort', 'newest' )==='newest' )>Mới nhất</option>
                <option value="oldest" @selected(request('sort')==='oldest' )>Cũ nhất</option>
                <option value="rent_asc" @selected(request('sort')==='rent_asc' )>Giá thuê tăng dần</option>
                <option value="rent_desc" @selected(request('sort')==='rent_desc' )>Giá thuê giảm dần</option>
            </select>

            <div class="filter-actions">
                <button type="submit" class="btn btn-brand">
                    <i class="fa-solid fa-filter"></i> Lọc
                </button>

                @if ($hasFilter)
                <a href="{{ route('contracts.index') }}" class="btn btn-ghost" title="Xóa bộ lọc" aria-label="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </section>

    {{-- Danh sách hợp đồng --}}
    <section class="panel table-panel">
        <div class="panel-head">
            <h2 class="panel-title">Danh sách hợp đồng</h2>
            @if ($contracts->total() > 0)
            <span class="panel-count">{{ number_format($contracts->total(), 0, ',', '.') }} hợp đồng</span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table contract-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Phòng</th>
                        <th>Người thuê</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th class="text-end">Giá thuê</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($contracts as $contract)
                    @php
                    $firstTenant = $contract->tenants->first();
                    $otherTenants = $contract->tenants->count() - 1;

                    $daysLeft = null;
                    if ($contract->status === 'active' && $contract->end_date) {
                    $daysLeft = (int) now()->startOfDay()
                    ->diffInDays($contract->end_date->copy()->startOfDay(), false);
                    }
                    @endphp

                    <tr>
                        <td>
                            <span class="contract-code">HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>

                        <td class="room-name">
                            {{ $contract->room->name ?? 'Không xác định' }}
                        </td>

                        <td>
                            @if ($firstTenant)
                            <div class="tenant">
                                <span class="tenant-avatar" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr(trim($firstTenant->full_name), 0, 1)) }}
                                </span>
                                <div class="tenant-info">
                                    <span class="tenant-name">{{ $firstTenant->full_name }}</span>
                                    @if ($otherTenants > 0)
                                    <span class="tenant-more">+{{ $otherTenants }} người khác</span>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="text-muted">Chưa có người thuê</span>
                            @endif
                        </td>

                        <td>{{ $contract->start_date?->format('d/m/Y') ?? '—' }}</td>

                        <td>
                            @if ($contract->end_date)
                            {{ $contract->end_date->format('d/m/Y') }}
                            @if (!is_null($daysLeft) && $daysLeft >= 0 && $daysLeft <= 30)
                                <span class="end-note">Còn {{ $daysLeft }} ngày</span>
                                @endif
                                @else
                                <span class="text-muted">Không thời hạn</span>
                                @endif
                        </td>

                        <td class="text-end rent-price">
                            {{ number_format($contract->rent_price ?? 0, 0, ',', '.') }} đ
                        </td>

                        <td>
                            <span class="status-pill is-{{ $contract->status }}">
                                {{ $statusLabels[$contract->status] ?? $contract->status }}
                            </span>
                        </td>


                        <td class="text-center">
                            <div class="contract-actions-dropdown">

                                {{-- Nút 3 chấm --}}
                                <button
                                    type="button"
                                    class="btn-icon btn-contract-menu-toggle"
                                    aria-expanded="false"
                                    title="Thao tác">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                {{-- Danh sách thao tác --}}
                                <div class="contract-actions-menu" hidden>
                                    <a
                                        href="{{ route('contracts.pdf', $contract->id) }}"
                                        class="contract-menu-item"
                                        target="_blank"
                                        title="Xuất PDF hợp đồng">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        <span>Xuất PDF</span>
                                    </a>
                                    {{-- Xem chi tiết --}}
                                    <button
                                        type="button"
                                        class="contract-menu-item btn-view-contract"
                                        data-id="{{ $contract->id }}"
                                        data-url="{{ route('contracts.show', $contract->id) }}">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Xem chi tiết</span>
                                    </button>

                                    {{-- Chuyển trạng thái --}}
                                    @if ($contract->status === 'draft')

                                    <button
                                        type="button"
                                        class="contract-menu-item btn-send-signature"
                                        data-id="{{ $contract->id }}"
                                        data-code="HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}"
                                        data-url="{{ route('contracts.send-for-signature', $contract->id) }}">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span>Gửi chờ ký</span>
                                    </button>

                                    @elseif ($contract->status === 'waiting_signature')

                                    <button
                                        type="button"
                                        class="contract-menu-item btn-activate-contract"
                                        data-id="{{ $contract->id }}"
                                        data-code="HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}"
                                        data-url="{{ route('contracts.activate', $contract->id) }}">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        <span>Xác nhận đã ký</span>
                                    </button>
                                    @elseif ($contract->status === 'waiting_owner_signature')

                                    <button
                                        type="button"
                                        class="contract-menu-item btn-owner-sign-contract"
                                        data-id="{{ $contract->id }}"
                                        data-code="HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}"
                                        data-url="{{ route('contracts.owner-sign', $contract->id) }}"
                                        data-detail-url="{{ route('contracts.show', $contract->id) }}">

                                        <i class="fa-solid fa-signature"></i>
                                        <span>Ký hợp đồng</span>
                                    </button>
                                    @elseif ($contract->status === 'active')

                                    <button
                                        type="button"
                                        class="contract-menu-item btn-terminate-contract"
                                        data-id="{{ $contract->id }}"
                                        data-code="HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}"
                                        data-url="{{ route('contracts.terminate', $contract->id) }}">
                                        <i class="fa-solid fa-file-circle-xmark"></i>
                                        <span>Chấm dứt hợp đồng</span>
                                    </button>

                                    @endif

                                    {{-- Chỉnh sửa và xóa --}}
                                    @if (in_array($contract->status, ['draft', 'waiting_signature']))

                                    <button
                                        type="button"
                                        class="contract-menu-item btn-edit-contract"
                                        data-id="{{ $contract->id }}"
                                        data-url="{{ route('contracts.edit-data', $contract->id) }}"
                                        data-update-url="{{ route('contracts.update', $contract->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Chỉnh sửa</span>
                                    </button>

                                    <button
                                        type="button"
                                        class="contract-menu-item danger btn-delete-contract"
                                        data-id="{{ $contract->id }}"
                                        data-code="HĐ-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}"
                                        data-url="{{ route('contracts.destroy', $contract->id) }}">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>Xóa hợp đồng</span>
                                    </button>

                                    @endif

                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fa-regular fa-file-lines"></i>
                            @if ($hasFilter)
                            <p class="empty-title">Không tìm thấy hợp đồng phù hợp</p>
                            <p class="empty-text">Thử đổi từ khóa hoặc <a href="{{ route('contracts.index') }}">xóa bộ lọc</a>.</p>
                            @else
                            <p class="empty-title">Chưa có hợp đồng nào</p>
                            <p class="empty-text">Hợp đồng thuê phòng sẽ hiển thị tại đây.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="panel-foot">
            <small class="text-muted">
                @if ($contracts->total() > 0)
                Hiển thị {{ $contracts->firstItem() }} – {{ $contracts->lastItem() }}
                trong tổng số {{ $contracts->total() }} hợp đồng
                @else
                Hiển thị 0 hợp đồng
                @endif
            </small>

            {{ $contracts->links('pagination::bootstrap-5') }}
        </div>
    </section>
</div>
@include('admin.contracts.partials.detail_modal')
@include('admin.contracts.partials.edit_modal')
@include('admin.contracts.partials.delete_modal')
@include('admin.contracts.partials.create_modal')

@endsection
{{--
    Lưu ý bên Controller: dùng ->paginate(10)->withQueryString()
    để khi chuyển trang vẫn giữ nguyên search / status / sort.
--}}