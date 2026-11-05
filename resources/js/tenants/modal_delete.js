// Modal xóa

const deleteModal = document.getElementById("deleteModal");
const deleteForm = document.getElementById("deleteForm");
const tenantName = document.getElementById("tenantName");

const btnCancel = document.getElementById("btnCancel");

const deleteButtons = document.querySelectorAll(".btn-delete");

// Mở modal
deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const url = this.dataset.url;
        const name = this.dataset.name;

        // Gán URL vào Form
        deleteForm.action = url;

        // Hiển thị tên người thuê
        tenantName.textContent = name;

        // Hiển thị modal
        deleteModal.classList.add("active");
    });
});

// Đóng modal khi nhấn Hủy
btnCancel.addEventListener("click", function () {
    deleteModal.classList.remove("active");
});

// Đóng modal khi nhấn bên ngoài
deleteModal.addEventListener("click", function (event) {
    if (event.target === deleteModal) {
        deleteModal.classList.remove("active");
    }
});
