(function () {
    // Chờ HTML tải xong
    document.addEventListener("DOMContentLoaded", function () {
        // ==============================
        // 1. LẤY CÁC PHẦN TỬ
        // ==============================
        const login = document.getElementById("login-card");
        const signup = document.getElementById("signup-card");
        const page = document.querySelector(".page");

        const pw = document.getElementById("password");
        const eye = document.getElementById("eye");
        const slash = document.getElementById("slash");

        // Không phải trang đăng nhập thì dừng
        if (!login && !signup && !pw) return;

        // ==============================
        // 2. HIỆN / ẨN MẬT KHẨU
        // ==============================
        if (pw && eye && slash) {
            function syncPassword() {
                slash.style.display = pw.type === "password" ? "" : "none";
            }

            syncPassword();

            eye.addEventListener("click", function () {
                pw.type = pw.type === "password" ? "text" : "password";

                eye.setAttribute(
                    "aria-label",
                    pw.type === "password" ? "Hiện mật khẩu" : "Ẩn mật khẩu",
                );

                syncPassword();
            });
        }

        // ==============================
        // 3. CHUYỂN ĐỔI ĐĂNG NHẬP / ĐĂNG KÝ
        // ==============================
        if (login && signup && page) {
            function route(focus = false) {
                const isSignup = location.hash === "#dang-ky";

                login.hidden = isSignup;
                signup.hidden = !isSignup;

                page.classList.toggle("signup-mode", isSignup);

                if (focus && isSignup) {
                    document.getElementById("name")?.focus({
                        preventScroll: true,
                    });
                }
            }

            window.addEventListener("hashchange", function () {
                route(true);
            });

            route();
        }

        // ==============================
        // 4. KIỂM TRA FORM ĐĂNG KÝ
        // ==============================
        const registerForm = document.getElementById("registerForm");

        if (registerForm) {
            registerForm.addEventListener("submit", function (e) {
                const name = document.getElementById("name")?.value.trim();

                const email = document
                    .getElementById("register-email")
                    ?.value.trim();

                const password = document.getElementById("pw1")?.value;

                const confirmPassword = document.getElementById("pw2")?.value;

                const agree = document.getElementById("agree")?.checked;

                const msg = document.getElementById("msg2");

                if (msg) msg.textContent = "";

                if (!name || !email || !password || !confirmPassword) {
                    e.preventDefault();
                    if (msg)
                        msg.textContent = "Vui lòng điền đầy đủ thông tin.";
                } else if (password.length < 6) {
                    e.preventDefault();
                    if (msg) msg.textContent = "Mật khẩu cần ít nhất 6 ký tự.";
                } else if (password !== confirmPassword) {
                    e.preventDefault();
                    if (msg) msg.textContent = "Mật khẩu nhập lại chưa khớp.";
                } else if (!agree) {
                    e.preventDefault();
                    if (msg)
                        msg.textContent =
                            "Vui lòng đồng ý với điều khoản sử dụng.";
                }
            });
        }

        // ==============================
        // 5. THÔNG BÁO SUCCESS / ERROR
        // ==============================
        const toast = document.getElementById("toastMessage");

        if (toast) {
            const closeBtn = toast.querySelector(".toast-close");

            function closeToast() {
                toast.classList.add("hide");

                setTimeout(function () {
                    toast.remove();
                }, 400);
            }

            const timer = setTimeout(closeToast, 3000);

            if (closeBtn) {
                closeBtn.addEventListener("click", function () {
                    clearTimeout(timer);
                    closeToast();
                });
            }
        }
    });
})();
