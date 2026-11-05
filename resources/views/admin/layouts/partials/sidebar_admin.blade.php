<aside class="sidebar">
    <img src="{{ asset('/build/assets/image/logo.png') }}" class="logo">
    <div class="menu-aside">
        <ul>
            <li>
                <a href="#" class="">
                    <p>
                        <i class="fa-solid fa-sliders"></i>
                        Điều khiển
                    </p>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-house-chimney"></i>
                        Quản lý nhà
                    </p>
                    <i class="fa-solid fa-angle-down"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-cart-arrow-down"></i>
                        Đơn hàng
                    </p>
                    <i class="fa-solid fa-angle-down"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('tenants.index') }}"
                    class="{{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                    <p>
                        <i class="fa-solid fa-user"></i>
                        Người thuê
                    </p>
                    <i class="fa-solid fa-angle-down"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-layer-group"></i>
                        Danh mục
                    </p>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-tag"></i>
                        Khuyến mãi
                    </p>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-file-pen"></i>
                        Báo cáo
                    </p>
                </a>
            </li>

            <li>
                <a href="#">
                    <p>
                        <i class="fa-solid fa-gear"></i>
                        Cài đặt
                    </p>
                    <i class="fa-solid fa-angle-down"></i>
                </a>
            </li>
        </ul>
    </div>
</aside>