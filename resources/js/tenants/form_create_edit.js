// Moddal

const modal = document.getElementById("tenantModal");
const form = document.getElementById("tenantForm");
const title = document.getElementById("modalTitle");
const formMethod = document.getElementById("formMethod");
const btnSubmit = document.getElementById("btnSubmit");
const fields = {
    full_name: document.getElementById("full_name"),
    phone: document.getElementById("phone"),
    email: document.getElementById("email"),
    identity_number: document.getElementById("identity_number"),
    identity_front: document.getElementById("identity_front"),
    identity_back: document.getElementById("identity_back"),
    date_of_birth: document.getElementById("date_of_birth"),
    gender: document.getElementById("gender"),
    permanent_address: document.getElementById("permanent_address"),
    note: document.getElementById("note"),
};

// Mở Modal
function openModal() {
    modal.classList.add("active");
}

// Đóng Modal
function closeModal() {
    modal.classList.remove("active");
}

// Xử lý chế độ Thêm
document.getElementById("btnAddTenant")?.addEventListener("click", function () {
    form.reset();

    form.action = "/admin/tenants";
    formMethod.value = "POST";

    title.textContent = "Thêm người thuê";
    btnSubmit.textContent = "Thêm người thuê";

    // Cho phép nhập dữ liệu
    Object.values(fields).forEach(function (field) {
        field.disabled = false;
        field.readOnly = false;
    });

    openModal();
});

// Xử lý chế độ Xem và Sửa
document.querySelectorAll(".js-tenant-action").forEach(function (btn) {
    btn?.addEventListener("click", function () {
        const mode = this.dataset.mode;

        // Đổ dữ liệu vào các input
        fields.full_name.value = this.dataset.name || "";
        fields.phone.value = this.dataset.phone || "";
        fields.email.value = this.dataset.email || "";
        fields.identity_number.value = this.dataset.identity || "";
        fields.date_of_birth.value = this.dataset.birth || "";
        fields.gender.value = this.dataset.gender || "";
        fields.permanent_address.value = this.dataset.address || "";
        fields.note.value = this.dataset.note || "";

        if (mode === "view") {
            title.textContent = "Thông tin người thuê";
            btnSubmit.style.display = "none";

            Object.values(fields).forEach((field) => {
                field.disabled = true;
            });
        } else if (mode === "edit") {
            title.textContent = "Cập nhật người thuê";
            btnSubmit.textContent = "Lưu thay đổi";
            btnSubmit.style.display = "inline-block";
            form.action = this.dataset.updateUrl;
            formMethod.value = "PUT";

            Object.values(fields).forEach((field) => {
                field.disabled = false;
            });
        }
        showCCCD(this.dataset.front, this.dataset.back);
        openModal();
    });
});

// Đóng Modal
document.getElementById("cancelModal")?.addEventListener("click", closeModal);

modal?.addEventListener("click", function (e) {
    if (e.target === modal) {
        closeModal();
    }
});

// xem ảnh CCCD trước

function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input?.addEventListener("change", function () {
        const file = this.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";

            preview.previousElementSibling;
            input.previousElementSibling.querySelector("i").style.display =
                "none";
        }
    });
}

previewImage("identity_front", "previewFront");
previewImage("identity_back", "previewBack");

// hiển thị ảnh khi xem, sửa
function showCCCD(front, back) {
    const previewFront = document.getElementById("previewFront");
    const previewBack = document.getElementById("previewBack");

    const frontBox = previewFront.closest(".upload-box");
    const backBox = previewBack.closest(".upload-box");

    previewFront.src = front || "";
    previewBack.src = back || "";

    previewFront.style.display = front ? "block" : "none";
    previewBack.style.display = back ? "block" : "none";

    frontBox.querySelector("i").style.display = front ? "none" : "block";
    backBox.querySelector("i").style.display = back ? "none" : "block";
}
