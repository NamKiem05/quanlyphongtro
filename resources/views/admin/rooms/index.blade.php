@extends('admin.layouts.main')

@section('content')

<style>

</style>
<main class="main tenant-page">
    <div class="container-fluid rooms-page">

        {{-- Tiêu đề --}}
        <div class="page-header">
            <h4 class="page-title">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z" />
                </svg>
                Quản lý phòng
            </h4>
            <p class="page-subtitle">Tổng quan và quản lý danh sách phòng trọ</p>
        </div>

        {{-- Thống kê --}}
        <div class="row g-3 mb-4 stat-grid">

            <div class="col-6 col-lg-3">
                <div class="card stat-card stat-total">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3L2 12h3v8h5v-5h4v5h5v-8h3L12 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Tổng số phòng</p>
                        <h3 class="stat-value">{{ $statistics['total'] }}</h3>
                    </div>
                    <div class="stat-bars"><span></span><span></span><span></span><span></span><span></span></div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card stat-available">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm-1.5 14.5L6 12l1.4-1.4 3.1 3.1 6.1-6.1L18 9l-7.5 7.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Phòng trống</p>
                        <h3 class="stat-value">{{ $statistics['available'] }}</h3>
                    </div>
                    <div class="stat-bars"><span></span><span></span><span></span><span></span><span></span></div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card stat-occupied">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Đang cho thuê</p>
                        <h3 class="stat-value">{{ $statistics['occupied'] }}</h3>
                    </div>
                    <div class="stat-bars"><span></span><span></span><span></span><span></span><span></span></div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card stat-maintenance">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Phòng bảo trì</p>
                        <h3 class="stat-value">{{ $statistics['maintenance'] }}</h3>
                    </div>
                    <div class="stat-bars"><span></span><span></span><span></span><span></span><span></span></div>
                </div>
            </div>

        </div>

        {{-- Tìm kiếm, lọc và thêm phòng --}}
        <div class="card filter-card mb-4">

            <form class="filter-form" action="{{ route('rooms.index') }}" method="GET">

                <div class="field-box field-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Tìm tên phòng..."
                        value="{{ request('search') }}">
                </div>

                <div class="field-box field-status">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 4h18l-7 8.5V20l-4-2v-5.5L3 4z" />
                    </svg>
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Phòng trống</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Đang thuê</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                    </select>
                </div>

                <div class="field-box field-sort">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 4v16M7 20l-3-3M7 20l3-3M17 20V4M17 4l-3 3M17 4l3 3" />
                    </svg>
                    <select name="sort" class="form-select">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                </div>

                <button type="submit" class="btn-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                    Tìm kiếm
                </button>

            </form>

            <button type="button" class="btn-add" id="openAddRoomModal">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.4"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Thêm phòng
            </button>

        </div>

        {{-- Danh sách phòng --}}
        <div class="card list-card">

            <div class="table-wrap">
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên phòng</th>
                                <th>Tầng</th>
                                <th>Diện tích</th>
                                <th>Giá thuê</th>
                                <th>Trạng thái</th>
                                <th>Số hợp đồng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($rooms as $room)

                            <tr>
                                <td class="index-col">{{ $rooms->firstItem() + $loop->index }}</td>

                                <td class="room-name">{{ $room->name }}</td>

                                <td>
                                    @if ($room->floor)
                                    {{ is_numeric($room->floor) ? 'Tầng ' . $room->floor : $room->floor }}
                                    @else
                                    <span class="text-na">Chưa cập nhật</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($room->area)
                                    {{ (float) $room->area }} m²
                                    @else
                                    <span class="text-na">Chưa cập nhật</span>
                                    @endif
                                </td>

                                <td class="room-price">
                                    {{ number_format($room->rent_price, 0, ',', '.') }} đ
                                </td>

                                <td>
                                    @if ($room->status === 'available')
                                    <span class="status-badge status-available">Phòng trống</span>
                                    @elseif ($room->status === 'occupied')
                                    <span class="status-badge status-occupied">Đang thuê</span>
                                    @else
                                    <span class="status-badge status-maintenance">Bảo trì</span>
                                    @endif
                                </td>

                                <td>{{ $room->contracts_count }}</td>

                                <td>
                                    <div class="actions">
                                        <button type="button"
                                            class="btn-action btn-view btn-room-detail"
                                            data-url="{{ route('rooms.detail', $room->id) }}"
                                            title="Xem chi tiết">

                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <a href="{{ Route::has('rooms.edit') ? route('rooms.edit', $room->id) : '#' }}"
                                            class="btn-action btn-edit" title="Chỉnh sửa">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </a>

                                        <form action="{{ Route::has('rooms.destroy') ? route('rooms.destroy', $room->id) : '#' }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Xóa">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="7" />
                                        <path d="M20 20l-3.5-3.5" />
                                        <path d="M8.5 11h5" />
                                    </svg>
                                    <p>Không tìm thấy phòng nào.</p>
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
                    @if ($rooms->total() > 0)
                    Hiển thị {{ $rooms->firstItem() }} - {{ $rooms->lastItem() }} trong tổng số {{ $rooms->total() }} phòng
                    @else
                    Hiển thị 0 phòng
                    @endif
                </div>
                <div class="list-links">
                    @if ($rooms->hasPages())
                    {{ $rooms->withQueryString()->links('pagination::bootstrap-4') }}
                    @endif
                </div>
            </div>

        </div>

    </div>
    @include('admin.rooms.partials.detail-modal')
    @include('admin.rooms.partials.create-modal')
</main>
<script>

</script>
@endsection