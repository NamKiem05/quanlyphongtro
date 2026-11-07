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

    <button type="button" id="openProfile" data-open-profile>
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
<style>
    :root {
    --blue-900: #1e3a8a;
    --blue-800: #1e40af;
    --blue-700: #1d4ed8;
    --blue-600: #2563eb;
    --blue-500: #3b82f6;
    --blue-400: #60a5fa;
    --blue-100: #dbeafe;
    --blue-50: #eff6ff;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --border-soft: #e2e8f0;
}

/* ---------- Wrapper & overlay ---------- */
.profile-admin {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    font-family: "Inter", "Segoe UI", system-ui, sans-serif;
}

.profile-admin.show {
    visibility: visible;
    opacity: 1;
}

.profile-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
}

/* ---------- Modal ---------- */
.profile-modal {
    position: relative;
    width: 100%;
    max-width: 440px;
    max-height: calc(100vh - 32px);
    overflow-y: auto;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(30, 58, 138, 0.3);
    transform: translateY(20px) scale(0.96);
    transition: transform 0.3s ease;
}

.profile-admin.show .profile-modal {
    transform: translateY(0) scale(1);
}

/* ---------- Header ---------- */
.profile-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 22px 24px 70px;
    background: linear-gradient(135deg, var(--blue-800), var(--blue-500));
    color: #fff;
}

.profile-header h3 {
    margin: 0 0 4px;
    font-size: 18px;
    font-weight: 700;
}

.profile-header p {
    margin: 0;
    font-size: 13px;
    color: var(--blue-100);
    opacity: 0.9;
}

.profile-close {
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.profile-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ---------- Avatar ---------- */
.profile-avatar {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: -52px;
    padding: 0 24px;
    text-align: center;
}

.avatar-circle {
    position: relative;
    width: 96px;
    height: 96px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-600), var(--blue-400));
    border: 5px solid #fff;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
    color: #fff;
    font-size: 36px;
    font-weight: 700;
}

.avatar-status {
    position: absolute;
    right: 4px;
    bottom: 6px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #22c55e;
    border: 3px solid #fff;
}

.profile-avatar h4 {
    margin: 14px 0 8px;
    font-size: 20px;
    font-weight: 700;
    color: var(--text-main);
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 600;
}

.role-badge.is-admin {
    background: var(--blue-50);
    color: var(--blue-700);
    border: 1px solid var(--blue-100);
}

.role-badge.is-staff {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid var(--border-soft);
}

/* ---------- Info list ---------- */
.profile-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 22px 24px 8px;
}

.profile-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border: 1px solid var(--border-soft);
    border-radius: 14px;
    background: #fff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.profile-item:hover {
    border-color: var(--blue-400);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.12);
    transform: translateY(-1px);
}

.profile-icon {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: var(--blue-50);
    color: var(--blue-600);
    font-size: 16px;
}

.profile-text {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.profile-text span {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
}

.profile-text strong {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--text-main);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.profile-text strong.is-empty {
    color: #94a3b8;
    font-weight: 500;
    font-style: italic;
}

/* ---------- Footer ---------- */
.profile-footer {
    display: flex;
    justify-content: flex-end;
    padding: 16px 24px 24px;
}

.btn-close-profile {
    padding: 11px 28px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(90deg, var(--blue-600), var(--blue-500));
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-close-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(59, 130, 246, 0.45);
}

.btn-close-profile:active {
    transform: translateY(0);
}

/* ---------- Responsive ---------- */
@media (max-width: 480px) {
    .profile-header {
        padding: 18px 18px 64px;
    }

    .profile-info {
        padding: 18px 16px 6px;
    }

    .profile-footer {
        padding: 12px 16px 20px;
    }

    .btn-close-profile {
        width: 100%;
    }
}
</style>
@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $roleLabel = $isAdmin ? 'Quản trị viên' : 'Nhân viên';
    $initial = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1));
@endphp

<div class="profile-admin" id="profileAdmin" role="dialog" aria-modal="true" aria-labelledby="profileTitle" aria-hidden="true">
    <div class="profile-overlay" id="profileOverlay"></div>

    <div class="profile-modal">
        {{-- Header --}}
        <div class="profile-header">
            <div class="profile-header-text">
                <h3 id="profileTitle">Thông tin tài khoản</h3>
                <p>Thông tin cá nhân của {{ $isAdmin ? 'quản trị viên' : 'nhân viên' }}</p>
            </div>

            <button type="button" class="profile-close" id="closeProfile" aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Avatar --}}
        <div class="profile-avatar">
            <div class="avatar-circle">
                <span>{{ $initial }}</span>
                <i class="avatar-status"></i>
            </div>

            <h4>{{ $user->name }}</h4>
            <span class="role-badge {{ $isAdmin ? 'is-admin' : 'is-staff' }}">
                <i class="fa-solid fa-shield-halved"></i>
                {{ $roleLabel }}
            </span>
        </div>

        {{-- Information --}}
        <div class="profile-info">
            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="profile-text">
                    <span>Họ và tên</span>
                    <strong>{{ $user->name }}</strong>
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="profile-text">
                    <span>Email</span>
                    <strong>{{ $user->email }}</strong>
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="profile-text">
                    <span>Số điện thoại</span>
                    @if (!empty($user->number_phone))
                        <strong>{{ $user->number_phone }}</strong>
                    @else
                        <strong class="is-empty">Chưa cập nhật</strong>
                    @endif
                </div>
            </div>

            <div class="profile-item">
                <div class="profile-icon">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <div class="profile-text">
                    <span>Vai trò</span>
                    <strong>{{ $roleLabel }}</strong>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="profile-footer">
            <button type="button" class="btn-close-profile" id="closeProfileBtn">
                Đóng
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('profileAdmin');
        if (!modal) return;

        function openProfile() {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeProfile() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        // Mở modal: gắn data-open-profile vào bất kỳ nút/link nào
        document.querySelectorAll('[data-open-profile]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                openProfile();
            });
        });

        ['closeProfile', 'closeProfileBtn', 'profileOverlay'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', closeProfile);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) closeProfile();
        });

        window.openProfile = openProfile;
        window.closeProfile = closeProfile;
    })();
</script>
