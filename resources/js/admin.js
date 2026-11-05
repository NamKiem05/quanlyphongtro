const heaDer = document.querySelector("header");
const sideBar = document.querySelector("aside");
const mainContent = document.querySelector(".main");
const tagMenu = document.querySelector("#tagMenu");

tagMenu.addEventListener("click", () => {
    heaDer.classList.toggle("active");
    sideBar.classList.toggle("active");
    mainContent.classList.toggle("active");
});

// quản lý tài khoản
const btnLogin = document.getElementById("btnLogin");
const menuLogin = document.querySelector(".menu-login");

btnLogin.addEventListener("click", function (e) {
    e.stopPropagation();
    menuLogin.classList.toggle("active");
});

menuLogin.addEventListener("click", function (e) {
    e.stopPropagation();
});

document.addEventListener("click", function () {
    menuLogin.classList.remove("active");
});


// Thông báo thành công và thất bại
document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("toastMessage");

    if (!toast) return;

    const closeBtn = toast.querySelector(".toast-close");

    // Hàm đóng thông báo
    function closeToast() {
        toast.classList.add("hide");

        setTimeout(() => {
            toast.remove();
        }, 400);
    }

    // Tự động đóng sau 3 giây
    const timer = setTimeout(closeToast, 3000);

    // Đóng khi nhấn nút X
    closeBtn.addEventListener("click", function () {
        clearTimeout(timer);
        closeToast();
    });
});
