<header class="header">
    <div class="box-tag">
        <i class="fa-solid fa-bars" id="tagMenu"></i>
    </div>
    <div class="box-header">
        <ul>
            <li onclick="toggleMessenger()"><i class="fa-regular fa-comment"></i></li>
            <li><i class="fa-regular fa-bell"></i></li>
        </ul>
        <div class="header-login" id="btnLogin">
            <div class="user-avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="admin">
                <p class="name-admin">{{ auth()->user()->name }}</p>
                <div class="status-active">
                    <p class="status-admin">Đang hoạt động</p>
                    <span class="status"></span>
                </div>
            </div>
            <i class="fa-solid fa-angle-down"></i>
        </div>
    </div>
</header>
<div class="menu-login">
    <div class="menu-user">
        <div class="user-avatar">
            <i class="fa-solid fa-user"></i>
        </div>

        <div class="user-info">
            <h3>{{ auth()->user()->name }}</h3>
            <span>
                {{ auth()->user()->role === 'admin' ? 'Chủ nhà' : 'Người thuê' }}
            </span>
        </div>
    </div>

    <div class="menu-divider"></div>

    <a href="#">
        <i class="fa-solid fa-user-gear"></i>
        <span>Thông tin tài khoản</span>
    </a>

    <a href="#">
        <i class="fa-solid fa-lock"></i>
        <span>Đổi mật khẩu</span>
    </a>

    <a href="#">
        <i class="fa-solid fa-gear"></i>
        <span>Cài đặt</span>
    </a>

    <div class="menu-divider"></div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button type="submit" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Đăng xuất</span>
        </button>
    </form>
</div>