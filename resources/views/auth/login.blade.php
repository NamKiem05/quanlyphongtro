<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Đăng nhập – QuảnLýNhà</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    @vite(['resources/css/auth/login.css'])
</head>

<body>
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <symbol id="house" viewBox="0 0 64 64">
                <path d="M6 32 32 8l26 24M12 28v28h14M52 28v28H38" fill="none" stroke="currentColor" stroke-width="5"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M26 30h5v5h-5zM33 30h5v5h-5zM26 37h5v5h-5z" fill="currentColor" />
                <path d="M26 56h12" stroke="currentColor" stroke-width="5" stroke-linecap="round" />
            </symbol>
        </defs>
    </svg>

    <main class="page">
        <section class="left">
            <div class="brand">
                <svg>
                    <use href="#house" style="color:#fff" />
                </svg>
                <div><b>QuảnLýNhà</b><span>Quản lý phòng trọ thông minh</span></div>
            </div>
            <div class="hero">
                <h1>Giải pháp quản lý<br>phòng trọ hiện đại</h1>
                <p>Dễ dàng quản lý, tiết kiệm thời gian, nâng cao hiệu quả kinh doanh.</p>
                <ul class="feats">
                    <li><i><svg viewBox="0 0 24 24" fill="#fff">
                                <path d="M13 2 4 14h6l-1 8 9-12h-6z" />
                            </svg></i>Quản lý nhanh chóng</li>
                    <li><i><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round"
                                stroke-linecap="round">
                                <path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z" />
                                <path d="m8.5 12 2.5 2.5 4.5-5" />
                            </svg></i>Bảo mật an toàn</li>
                    <li><i><svg viewBox="0 0 24 24" fill="#fff">
                                <rect x="4" y="13" width="3.5" height="7" rx="1" />
                                <rect x="10" y="9" width="3.5" height="11" rx="1" />
                                <rect x="16" y="5" width="3.5" height="15" rx="1" />
                            </svg></i>Thống kê chi tiết</li>
                </ul>
            </div>
        </section>

        <section class="card" id="login-card" aria-labelledby="t">
            <div class="logo"><svg style="color:#fff">
                    <use href="#house" />
                </svg></div>
            <h2 id="t">Đăng nhập hệ thống</h2>
            <p class="sub">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục</p>
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="8" r="4.2" />
                        <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z" />
                    </svg>
                    <input placeholder="Tên đăng nhập hoặc Email" autocomplete="username" id="login-email" class="block mt-1 w-full"
                        type="email" name="email" :value="old('email')" required autofocus aria-label="Tên đăng nhập hoặc Email">
                </div>
                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7 10V7.5a5 5 0 0 1 10 0V10h1.5a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-13a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm2 0h6V7.5a3 3 0 0 0-6 0z" />
                    </svg>
                    <input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="Mật khẩu"
                        autocomplete="current-password" aria-label="Mật khẩu">
                    <button type="button" id="eye" aria-label="Hiện mật khẩu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path id="eyep" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" />
                            <circle cx="12" cy="12" r="3" />
                            <path id="slash" d="m4 4 16 16" />
                        </svg>
                    </button>
                </div>

                <div class="row">
                    <label><input type="checkbox"> Ghi nhớ đăng nhập</label>
                    <a href="#quen-mat-khau">Quên mật khẩu?</a>
                </div>
                <p class="msg" id="msg" role="alert"></p>

                <button class="primary" id="go" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 12h16M13 6l6 6-6 6" />
                    </svg>
                    Đăng nhập
                </button>
            </form>
            <div class="or">Hoặc đăng nhập bằng</div>
            <button class="google" type="button">
                <svg viewBox="0 0 48 48">
                    <path fill="#EA4335"
                        d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z" />
                    <path fill="#4285F4"
                        d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z" />
                    <path fill="#FBBC05"
                        d="M10.5 28.7c-.5-1.4-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.4 0 20.1 0 24s.9 7.6 2.6 10.8z" />
                    <path fill="#34A853"
                        d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.5-5.8c-2.1 1.4-4.8 2.3-8.4 2.3-6.3 0-11.6-4.1-13.5-9.8l-7.9 6.1C6.500 42.600 14.600 48 24 48z" />
                </svg>
                Đăng nhập với Google
            </button>
            <p class="reg">Chưa có tài khoản? <a href="#dang-ky">Đăng ký ngay</a></p>
        </section>

        <section class="card signup" id="signup-card" aria-labelledby="t2" hidden>
            <div class="logo"><svg style="color:#fff">
                    <use href="#house" />
                </svg></div>
            <h2 id="t2">Tạo tài khoản mới</h2>
            <p class="sub">Điền thông tin bên dưới để bắt đầu quản lý phòng trọ</p>
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="8" r="4.2" />
                        <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z" />
                    </svg>
                    <input id="name" type="text" placeholder="Họ và tên" aria-label="Họ và tên" class="block mt-1 w-full" name="name" :value="old('name')" required autofocus autocomplete="name">
                </div>
                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="m3.5 7 8.500 6 8.500-6" />
                    </svg>
                    <input id="register-email" placeholder="Email" aria-label="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username">
                </div>
                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7 10V7.5a5 5 0 0 1 10 0V10h1.5a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-13a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm2 0h6V7.5a3 3 0 0 0-6 0z" />
                    </svg>
                    <input id="pw1" type="password" placeholder="Mật khẩu" aria-label="Mật khẩu" class="block mt-1 w-full" name="password" required autocomplete="new-password">
                </div>
                <div class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7 10V7.5a5 5 0 0 1 10 0V10h1.5a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-13a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm2 0h6V7.5a3 3 0 0 0-6 0z" />
                    </svg>
                    <input id="pw2" type="password" placeholder="Nhập lại mật khẩu" aria-label="Nhập lại mật khẩu" class="block mt-1 w-full" name="password_confirmation" required autocomplete="new-password">
                </div>

                <label class="terms"><input type="checkbox" id="agree"> <span>Tôi đồng ý với <a href="#dieu-khoan">điều khoản sử
                            dụng</a></span></label>
                <p class="msg" id="msg2" role="alert"></p>

                <button class="primary" id="signup-go" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 12h16M13 6l6 6-6 6" />
                    </svg>
                    Đăng ký
                </button>
            </form>
            <p class="reg">Đã có tài khoản? <a href="#dang-nhap">Đăng nhập</a></p>
        </section>

        <div class="copy">© 2026 QuảnLýNhà. Tất cả quyền được bảo lưu.</div>
    </main>

    <!-- {{-- Toast thông báo thành công --}} -->
    @if (session('success'))
    <div class="toast-message success" id="toastMessage">
        <div class="toast-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="toast-content">
            <strong>Thành công</strong>
            <p>{{ session('success') }}</p>
        </div>

        <button type="button" class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- {{-- Toast thông báo thất bại --}} -->
    @if (session('error'))
    <div class="toast-message error" id="toastMessage">
        <div class="toast-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>

        <div class="toast-content">
            <strong>Thất bại</strong>
            <p>{{ session('error') }}</p>
        </div>

        <button type="button" class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
@vite(['resources/js/auth/login.js'])
</body>

</html>