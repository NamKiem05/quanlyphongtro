<div class="contract-delete-modal" id="deleteContractModal">
    <div class="contract-delete-overlay" data-close-delete></div>

    <div class="contract-delete-content">
        <div class="contract-delete-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <h3>Xác nhận xóa hợp đồng</h3>

        <p>
            Anh có chắc chắn muốn xóa hợp đồng
            <strong id="deleteContractCode"></strong> không?
        </p>

        <p class="text-muted">
            Hành động này không thể hoàn tác.
        </p>

        <div class="contract-delete-error d-none" id="deleteContractError"></div>

        <div class="contract-delete-actions">
            <button type="button"
                class="btn btn-ghost"
                data-close-delete>
                Hủy
            </button>

            <button type="button"
                class="btn btn-danger"
                id="btnConfirmDeleteContract">
                <i class="fa-solid fa-trash"></i>
                Xóa hợp đồng
            </button>
        </div>
    </div>
    <form id="deleteContractForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Lấy các phần tử modal
        const modal = document.getElementById("deleteContractModal");

        if (!modal) return;

        const contractCode = document.getElementById("deleteContractCode");
        const errorElement = document.getElementById("deleteContractError");
        const confirmButton = document.getElementById("btnConfirmDeleteContract");
        const deleteForm = document.getElementById("deleteContractForm");

        // 2. Lưu URL hợp đồng cần xóa
        let deleteUrl = null;

        // 3. Mở modal khi nhấn nút xóa
        document.addEventListener("click", function(event) {
            const deleteButton = event.target.closest(".btn-delete-contract");

            if (!deleteButton) return;

            deleteUrl = deleteButton.dataset.url;

            contractCode.textContent = deleteButton.dataset.code || "";

            errorElement.textContent = "";
            errorElement.classList.add("d-none");

            modal.classList.add("active");
        });

        // 4. Đóng modal
        function closeDeleteModal() {
            modal.classList.remove("active");

            deleteUrl = null;

            errorElement.textContent = "";
            errorElement.classList.add("d-none");
        }

        // Đóng khi nhấn nút Hủy hoặc overlay
        modal.querySelectorAll("[data-close-delete]").forEach(function(element) {
            element.addEventListener("click", closeDeleteModal);
        });

        // Đóng khi nhấn Escape
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape" && modal.classList.contains("active")) {
                closeDeleteModal();
            }
        });

        // 5. Xử lý xác nhận xóa
        confirmButton.addEventListener("click", async function() {
            if (!deleteUrl) return;

            const originalContent = confirmButton.innerHTML;

            confirmButton.disabled = true;
            confirmButton.textContent = "Đang xóa...";

            errorElement.textContent = "";
            errorElement.classList.add("d-none");

            try {
                // Lấy CSRF token và method DELETE từ form ẩn
                const formData = new FormData(deleteForm);

                const response = await fetch(deleteUrl, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    let message = result.message || "Không thể xóa hợp đồng.";

                    // Lấy lỗi validation nếu Laravel trả về
                    if (result.errors) {
                        const errors = Object.values(result.errors).flat();
                        message = errors[0] || message;
                    }

                    throw new Error(message);
                }

                // 6. Xóa thành công
                closeDeleteModal();

                window.showToast("success", result.message);

                // Tải lại danh sách sau khi hiển thị thông báo
                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            } catch (error) {
                // 7. Hiển thị lỗi ngay trong modal
                errorElement.textContent = error.message;
                errorElement.classList.remove("d-none");

            } finally {
                confirmButton.disabled = false;
                confirmButton.innerHTML = originalContent;
            }
        });
    });
</script>