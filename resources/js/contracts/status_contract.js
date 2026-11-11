document.addEventListener("DOMContentLoaded", () => {
    // Xử lý chuyển trạng thái hợp đồng
    document.addEventListener("click", async (event) => {
        const button = event.target.closest(
            ".btn-send-signature, .btn-activate-contract, .btn-terminate-contract",
        );

        if (!button) return;

        const contractId = button.dataset.id;
        const contractCode = button.dataset.code;
        const url = button.dataset.url;

        let action = "";
        let confirmMessage = "";

        // Xác định thao tác
        if (button.classList.contains("btn-send-signature")) {
            action = "Gửi chờ ký";
            confirmMessage = `Anh có chắc muốn gửi hợp đồng ${contractCode} chờ ký không?`;
        } else if (button.classList.contains("btn-activate-contract")) {
            action = "Xác nhận đã ký";
            confirmMessage = `Anh xác nhận hợp đồng ${contractCode} đã được ký và muốn kích hoạt?`;
        } else if (button.classList.contains("btn-terminate-contract")) {
            action = "Chấm dứt";
            confirmMessage = `Anh có chắc muốn chấm dứt hợp đồng ${contractCode} không?`;
        }

        // Xác nhận trước khi thực hiện
        if (!confirm(confirmMessage)) return;

        try {
            button.disabled = true;

            const response = await fetch(url, {
                method: "PATCH",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || "Không thể cập nhật trạng thái hợp đồng.",
                );
            }

            // Thông báo thành công
            if (typeof window.showToast === "function") {
                window.showToast("success", result.message);
            }

            // Tải lại danh sách để cập nhật trạng thái và thao tác
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } catch (error) {
            console.error("Lỗi chuyển trạng thái:", error);

            if (typeof window.showToast === "function") {
                window.showToast("error", error.message);
            } else {
                alert(error.message);
            }

            button.disabled = false;
        }
    });
});
