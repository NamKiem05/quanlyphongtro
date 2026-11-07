document.addEventListener("DOMContentLoaded", function () {
    // 1. Lấy các phần tử HTML
    const modalElement = document.getElementById("roomDetailModal");

    if (!modalElement) return;

    const loading = document.getElementById("roomDetailLoading");
    const content = document.getElementById("roomDetailContent");
    const error = document.getElementById("roomDetailError");

    const closeButton = document.getElementById("closeRoomModal");
    const overlay = modalElement.querySelector(".room-modal-overlay");

    // 2. Hàm mở modal
    function openRoomModal() {
        modalElement.classList.add("active");
        document.body.classList.add("room-modal-open");
    }

    // 3. Hàm đóng modal
    function closeRoomModal() {
        modalElement.classList.remove("active");
        document.body.classList.remove("room-modal-open");
    }

    // 4. Sự kiện đóng modal
    closeButton?.addEventListener("click", closeRoomModal);

    overlay?.addEventListener("click", closeRoomModal);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeRoomModal();
        }
    });

    // 5. Định dạng tiền
    function formatMoney(value) {
        return Number(value || 0).toLocaleString("vi-VN") + " VNĐ";
    }

    // 6. Định dạng ngày
    function formatDate(value) {
        if (!value) return "Không xác định";

        const date = new Date(value);

        if (isNaN(date.getTime())) return "Không xác định";

        return date.toLocaleDateString("vi-VN");
    }

    // 7. Trạng thái phòng
    function getRoomStatus(status) {
        const statuses = {
            available: "Còn trống",
            occupied: "Đang thuê",
            maintenance: "Đang bảo trì",
        };

        return statuses[status] || status;
    }

    // 8. Trạng thái hợp đồng
    function getContractStatus(status) {
        const statuses = {
            draft: "Bản nháp",
            waiting_signature: "Chờ ký",
            active: "Đang hiệu lực",
            expired: "Đã hết hạn",
            terminated: "Đã chấm dứt",
        };

        return statuses[status] || status;
    }

    // 9. Chống XSS
    function escapeHTML(value) {
        return String(value ?? "").replace(
            /[&<>"']/g,
            (char) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#039;",
                })[char],
        );
    }

    // 10. Hiển thị thông tin phòng
    function renderRoom(room) {
        document.getElementById("detailRoomName").textContent =
            room.name || "—";

        document.getElementById("detailRoomFloor").textContent =
            room.floor || "—";

        document.getElementById("detailRoomArea").textContent = room.area
            ? room.area + " m²"
            : "—";

        document.getElementById("detailRoomPrice").textContent = formatMoney(
            room.rent_price,
        );

        document.getElementById("detailRoomStatus").textContent = getRoomStatus(
            room.status,
        );

        document.getElementById("detailRoomCreated").textContent = formatDate(
            room.created_at,
        );

        document.getElementById("detailRoomDescription").textContent =
            room.description || "Không có mô tả";
    }

    // 11. Hiển thị người thuê hiện tại
    function renderTenants(contracts) {
        const container = document.getElementById("detailTenants");

        const activeContracts = contracts.filter(
            (contract) => contract.status === "active",
        );

        const tenants = activeContracts.flatMap(
            (contract) => contract.tenants || [],
        );

        if (tenants.length === 0) {
            container.innerHTML = `
                <p class="text-muted mb-0">
                    Hiện tại phòng chưa có người thuê.
                </p>
            `;
            return;
        }

        container.innerHTML = tenants
            .map(
                (tenant) => `
            <div class="tenant-detail-item">
                <div>
                    <strong>${escapeHTML(tenant.full_name)}</strong>
                    <p class="mb-0 text-muted">
                        ${escapeHTML(tenant.phone || "Chưa có số điện thoại")}
                    </p>
                     
                </div>
               <span class="badge ${tenant.pivot?.is_representative ? "bg-success" : "bg-secondary"}">
                    ${tenant.pivot?.is_representative ? "Đại diện" : "Thành viên"}
                </span>
            </div>
        `,
            )
            .join("");
    }

    // 12. Hiển thị lịch sử hợp đồng
    function renderContracts(contracts) {
        const tbody = document.getElementById("detailContracts");

        if (!contracts || contracts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Phòng chưa có hợp đồng.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = contracts
            .map((contract) => {
                const tenantNames = (contract.tenants || [])
                    .map((tenant) => escapeHTML(tenant.full_name))
                    .join(", ");

                return `
                <tr>
                    <td>#${contract.id}</td>
                    <td>${tenantNames || "Chưa có người thuê"}</td>
                    <td>${formatDate(contract.start_date)}</td>
                    <td>${formatDate(contract.end_date)}</td>
                    <td>${formatMoney(contract.deposit_amount)}</td>
                    <td>
                        <span class="badge ${contract.status === "active" ? "bg-success" : "bg-danger"}">
                            ${escapeHTML(getContractStatus(contract.status))}
                        </span>
                    </td>
                </tr>
            `;
            })
            .join("");
    }

    // 13. Sự kiện nhấn nút xem chi tiết
    document.addEventListener("click", async function (event) {
        const button = event.target.closest(".btn-room-detail");

        if (!button) return;

        const url = button.dataset.url;

        if (!url) {
            console.error("Không tìm thấy URL chi tiết phòng");
            return;
        }

        // Mở modal
        openRoomModal();

        // Hiển thị loading
        loading.classList.remove("d-none");
        content.classList.add("d-none");
        error.classList.add("d-none");

        try {
            // Gọi API Laravel
            const response = await fetch(url, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                throw new Error("Không thể tải thông tin phòng.");
            }

            const result = await response.json();

            if (!result.success || !result.data) {
                throw new Error("Dữ liệu phòng không hợp lệ.");
            }

            const room = result.data;

            // Hiển thị dữ liệu
            renderRoom(room);
            renderTenants(room.contracts || []);
            renderContracts(room.contracts || []);

            loading.classList.add("d-none");
            content.classList.remove("d-none");
        } catch (err) {
            loading.classList.add("d-none");

            error.textContent = err.message;
            error.classList.remove("d-none");

            console.error("Lỗi tải chi tiết phòng:", err);
        }
    });

    // Thêm phòng

    // ========================================
    // 14. CHỨC NĂNG THÊM PHÒNG
    // ========================================

    const createModal = document.getElementById("createRoomModal");
    const createForm = document.getElementById("createRoomForm");
    const openCreateButton = document.getElementById("openAddRoomModal");
    const submitButton = document.getElementById("btnSubmitRoom");
    const createError = document.getElementById("createRoomError");

    // Mở modal thêm phòng
    function openCreateRoomModal() {
        createForm.reset();
        clearCreateRoomErrors();

        createError.classList.add("d-none");
        createModal.classList.add("active");
        document.body.classList.add("room-modal-open");
    }

    // Đóng modal thêm phòng
    function closeCreateRoomModal() {
        createModal.classList.remove("active");
        document.body.classList.remove("room-modal-open");
    }

    // Nút mở modal
    openCreateButton?.addEventListener("click", openCreateRoomModal);

    // Các nút đóng modal
    createModal
        .querySelectorAll("[data-create-room-close]")
        .forEach((button) => {
            button.addEventListener("click", closeCreateRoomModal);
        });

    // Xóa lỗi validation cũ
    function clearCreateRoomErrors() {
        createForm.querySelectorAll(".field-error").forEach((element) => {
            element.textContent = "";
        });

        createForm.querySelectorAll(".is-invalid").forEach((element) => {
            element.classList.remove("is-invalid");
        });
    }

    // Hiển thị lỗi validation
    function showCreateRoomErrors(errors) {
        clearCreateRoomErrors();

        Object.entries(errors).forEach(([field, messages]) => {
            const input = createForm.querySelector(`[name="${field}"]`);
            const errorElement = createForm.querySelector(
                `[data-error="${field}"]`,
            );

            if (input) {
                input.classList.add("is-invalid");
            }

            if (errorElement) {
                errorElement.textContent = messages[0];
            }
        });
    }

    // Gửi form thêm phòng
    createForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        clearCreateRoomErrors();
        createError.classList.add("d-none");

        const formData = new FormData(createForm);
        const url = createForm.dataset.url;

        submitButton.disabled = true;
        submitButton.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Đang xử lý...
    `;

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            // Lỗi validate Laravel
            if (response.status === 422) {
                showCreateRoomErrors(result.errors || {});
                return;
            }

            if (!response.ok) {
                throw new Error(result.message || "Không thể thêm phòng.");
            }

            if (!result.success) {
                throw new Error(result.message || "Thêm phòng thất bại.");
            }

            alert("Thêm phòng thành công!");

            closeCreateRoomModal();
            window.location.reload();
        } catch (err) {
            console.error("Lỗi thêm phòng:", err);

            createError.textContent = err.message;
            createError.classList.remove("d-none");
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = `
            <i class="fa-solid fa-plus"></i>
            <span>Thêm phòng</span>
        `;
        }
    });
});
