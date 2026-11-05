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
                {{ auth()->user()->role === 'admin' ? 'Quản lý' : 'Nhân viên' }}
            </span>
        </div>
    </div>

    <div class="menu-divider"></div>

    <button type="button" id="openProfile">
        <i class="fa-solid fa-user"></i>
        Tài khoản của tôi
    </button>

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

<div class="profile-admin" id="profileAdmin">
    <div class="profile-overlay"></div>

    <div class="profile-modal">
        {{-- Header --}}
        <div class="profile-header">
            <div>
                <h3>Thông tin tài khoản</h3>
                <p>Thông tin cá nhân của quản trị viên</p>
            </div>

            <button type="button" class="profile-close" id="closeProfile">
                &times;
            </button>
        </div>

        {{-- Avatar --}}
        <div class="profile-avatar">
            <div class="avatar-circle">
                <i class="fa-solid fa-user"></i>
            </div>

            <h4>{{ Auth::user()->name }}</h4>
            <span>{{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên' }}</span>
        </div>

        {{-- Information --}}
        <div class="profile-info">
            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <span>Họ và tên</span>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <span>Email</span>
                    <strong>{{ Auth::user()->email }}</strong>
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <span>Số điện thoại</span>
                    <strong>{{ Auth::user()->number_phone ?? 'Chưa cập nhật' }}</strong>
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <span>Vai trò</span>
                    <strong class="role-admin">
                        {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Người thuê' }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="profile-footer">
            <button type="button" id="closeProfileBtn">
                Đóng
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('profileAdmin');

    // Không có modal thì dừng
    if (!modal) return;

    const closeBtn = document.getElementById('closeProfile');
    const closeFooter = document.getElementById('closeProfileBtn');
    const overlay = modal.querySelector('.profile-overlay');

    // Hàm đóng modal
    function closeModal() {
        modal.classList.remove('active');
    }

    closeBtn?.addEventListener('click', closeModal);
    closeFooter?.addEventListener('click', closeModal);
    overlay?.addEventListener('click', closeModal);

    // Nhấn ESC để đóng
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Mở modal
    const openBtn = document.getElementById('openProfile');

    openBtn?.addEventListener('click', function () {
        modal.classList.add('active');
    });

});
</script>