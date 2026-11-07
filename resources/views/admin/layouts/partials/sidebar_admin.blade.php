<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('/build/assets/image/logo.png') }}" alt="Logo" class="logo">
    </div>

    <nav class="menu-aside" aria-label="Menu chính">
        <p class="menu-title">Menu</p>

        <ul class="menu-list">
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-label">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Điều khiển</span>
                    </span>
                </a>
            </li>
            @php $roomActive = request()->routeIs('rooms.*'); @endphp
            <li class="menu-item has-sub {{ $roomActive ? 'open' : '' }}">
                <a href="" class="menu-link {{ $roomActive ? 'active' : '' }}" aria-expanded="{{ $roomActive ? 'true' : 'false' }}">
                    <span class="menu-label">
                        <i class="fa-solid fa-house-chimney"></i>
                        <span>Quản lý phòng</span>
                    </span>
                    <i class="fa-solid fa-angle-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Danh sách phòng</a></li>
                    <li><a href="#">Danh sách phòng</a></li>
                </ul>
            </li>

            <li class="menu-item has-sub">
                <a href="#" class="menu-link" aria-expanded="false">
                    <span class="menu-label">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                        <span>Đơn hàng</span>
                    </span>
                    <i class="fa-solid fa-angle-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#">Tất cả đơn hàng</a></li>
                    <li><a href="#">Đơn chờ xử lý</a></li>
                </ul>
            </li>

            @php $tenantActive = request()->routeIs('tenants.*'); @endphp
            <li class="menu-item has-sub {{ $tenantActive ? 'open' : '' }}">
                <a href="#" class="menu-link {{ $tenantActive ? 'active' : '' }}"
                    aria-expanded="{{ $tenantActive ? 'true' : 'false' }}">
                    <span class="menu-label">
                        <i class="fa-solid fa-user"></i>
                        <span>Người thuê</span>
                    </span>
                    <i class="fa-solid fa-angle-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('tenants.index') }}"
                            class="{{ request()->routeIs('tenants.index') ? 'active' : '' }}">
                            Danh sách người thuê
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-label">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Danh mục</span>
                    </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-label">
                        <i class="fa-solid fa-tag"></i>
                        <span>Khuyến mãi</span>
                    </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-label">
                        <i class="fa-solid fa-file-pen"></i>
                        <span>Báo cáo</span>
                    </span>
                </a>
            </li>

            <li class="menu-item has-sub">
                <a href="#" class="menu-link" aria-expanded="false">
                    <span class="menu-label">
                        <i class="fa-solid fa-gear"></i>
                        <span>Cài đặt</span>
                    </span>
                    <i class="fa-solid fa-angle-down arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#">Tài khoản</a></li>
                    <li><a href="#">Hệ thống</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>

<script>
    document.querySelectorAll('.sidebar .has-sub > .menu-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var item = link.parentElement;
            var isOpen = item.classList.toggle('open');
            link.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
</script>