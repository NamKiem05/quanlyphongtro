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
window.showToast = function (type, message) {
    // Xóa Toast cũ nếu đang tồn tại
    document.querySelectorAll(".toast-message").forEach(function (toast) {
        toast.remove();
    });

    const toast = document.createElement("div");
    toast.className = `toast-message ${type}`;
    toast.id = "toastMessage";

    const icon = document.createElement("div");
    icon.className = "toast-icon";

    const iconElement = document.createElement("i");
    iconElement.className =
        type === "success"
            ? "fa-solid fa-circle-check"
            : "fa-solid fa-circle-exclamation";

    icon.appendChild(iconElement);

    const toastContent = document.createElement("div");
    toastContent.className = "toast-content";

    const title = document.createElement("strong");
    title.textContent = type === "success" ? "Thành công" : "Thất bại";

    const messageElement = document.createElement("p");
    messageElement.textContent = message;

    toastContent.append(title, messageElement);

    const closeButton = document.createElement("button");
    closeButton.type = "button";
    closeButton.className = "toast-close";
    closeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';

    toast.append(icon, toastContent, closeButton);
    document.body.appendChild(toast);

    function closeToast() {
        toast.classList.add("hide");

        setTimeout(function () {
            toast.remove();
        }, 400);
    }

    const timer = setTimeout(closeToast, 3000);

    closeButton.addEventListener("click", function () {
        clearTimeout(timer);
        closeToast();
    });
}
console.log("ADMIN.JS ĐÃ ĐƯỢC LOAD");
console.log("showToast:", typeof window.showToast);