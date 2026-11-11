
document.addEventListener("DOMContentLoaded", function () {

    // ========================================
    // 1. LẤY CÁC PHẦN TỬ HTML
    // ========================================

    const modal = document.getElementById("contractCreateModal");

    // Không có modal thì dừng
    if (!modal) return;

    const form = document.getElementById("createContractForm");

    const loading = document.getElementById("contractCreateLoading");
    const error = document.getElementById("contractCreateError");
    const content = document.getElementById("contractCreateContent");

    const roomSelect = document.getElementById("createRoom");
    const roomFloor = document.getElementById("createRoomFloor");
    const roomArea = document.getElementById("createRoomArea");
    const rentPrice = document.getElementById("createRentPrice");

    const tenantList = document.getElementById("createTenantList");

    const submitButton = document.getElementById("btnCreateContractSubmit");

    // URL API
    const createDataUrl = modal.dataset.createDataUrl;
    const storeUrl = form.action;


    // ========================================
    // 2. MỞ VÀ ĐÓNG MODAL
    // ========================================

    function openModal() {
        modal.classList.add("active");

        resetForm();
        loadCreateData();
    }

    function closeModal() {
        modal.classList.remove("active");
    }

    // Mở modal bằng phần tử có data-open-create
    document.addEventListener("click", function (e) {

        const button = e.target.closest("[data-open-create]");

        if (!button) return;

        e.preventDefault();
        openModal();
    });

    // Đóng modal
    modal.querySelectorAll("[data-close-create]").forEach(button => {
        button.addEventListener("click", closeModal);
    });

    // Nhấn ESC để đóng
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && modal.classList.contains("active")) {
            closeModal();
        }
    });


    // ========================================
    // 3. RESET FORM
    // ========================================

    function resetForm() {

        form.reset();

        roomSelect.innerHTML = `
            <option value="">-- Chọn phòng thuê --</option>
        `;

        roomFloor.value = "";
        roomFloor.placeholder = "Chưa chọn phòng";

        roomArea.value = "";
        roomArea.placeholder = "Chưa chọn phòng";

        rentPrice.value = "";

        tenantList.innerHTML = "";

        clearErrors();

        loading.hidden = true;
        error.hidden = true;
        content.hidden = true;

        submitButton.disabled = false;
    }


    // ========================================
    // 4. HIỂN THỊ LOADING VÀ LỖI
    // ========================================

    function showLoading() {
        loading.hidden = false;
        error.hidden = true;
        content.hidden = true;
    }

    function showContent() {
        loading.hidden = true;
        error.hidden = true;
        content.hidden = false;
    }

    function showLoadError(message) {
        loading.hidden = true;
        content.hidden = true;
        error.hidden = false;

        const messageElement = error.querySelector("p");

        if (messageElement) {
            messageElement.textContent = message;
        }
    }


    // ========================================
    // 5. LẤY DỮ LIỆU PHÒNG VÀ NGƯỜI THUÊ
    // ========================================

    async function loadCreateData() {

        showLoading();

        try {

            const response = await fetch(createDataUrl, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || "Không thể tải dữ liệu."
                );
            }

            // Render danh sách phòng
            renderRooms(result.data.rooms);

            // Render danh sách người thuê
            renderTenants(result.data.tenants);

            showContent();

        } catch (err) {

            console.error("Lỗi tải dữ liệu tạo hợp đồng:", err);

            showLoadError(
                err.message || "Không thể tải dữ liệu tạo hợp đồng."
            );
        }
    }


    // ========================================
    // 6. RENDER DANH SÁCH PHÒNG
    // ========================================

    function renderRooms(rooms) {

        roomSelect.innerHTML = `
            <option value="">-- Chọn phòng thuê --</option>
        `;

        if (!rooms || rooms.length === 0) {

            roomSelect.innerHTML = `
                <option value="">Không có phòng trống</option>
            `;

            return;
        }

        rooms.forEach(room => {

            const option = document.createElement("option");

            option.value = room.id;
            option.textContent = room.name;

            // Lưu thông tin phòng vào data attribute
            option.dataset.floor = room.floor ?? "";
            option.dataset.area = room.area ?? "";
            option.dataset.rentPrice = room.rent_price ?? "";

            roomSelect.appendChild(option);
        });
    }


    // ========================================
    // 7. KHI CHỌN PHÒNG
    // ========================================

    roomSelect.addEventListener("change", function () {

        const selectedOption = this.selectedOptions[0];

        if (!this.value || !selectedOption) {

            roomFloor.value = "";
            roomArea.value = "";
            rentPrice.value = "";

            return;
        }

        const floor = selectedOption.dataset.floor;
        const area = selectedOption.dataset.area;
        const price = selectedOption.dataset.rentPrice;

        roomFloor.value = floor ? `Tầng ${floor}` : "Chưa cập nhật";

        roomArea.value = area ? `${area} m²` : "Chưa cập nhật";

        // Tự động điền giá thuê
        rentPrice.value = price;

        clearFieldError("room_id");
        clearFieldError("rent_price");
    });


    // ========================================
    // 8. RENDER DANH SÁCH NGƯỜI THUÊ
    // ========================================

    function renderTenants(tenants) {

        tenantList.innerHTML = "";

        if (!tenants || tenants.length === 0) {

            tenantList.textContent = "Không có người thuê khả dụng.";

            return;
        }

        tenants.forEach((tenant, index) => {

            const item = document.createElement("div");
            item.className = "contract-create-tenant-item";

            const checkboxLabel = document.createElement("label");
            checkboxLabel.className = "contract-create-tenant-info";

            const checkbox = document.createElement("input");

            checkbox.type = "checkbox";
            checkbox.name = "tenant_ids[]";
            checkbox.value = tenant.id;

            const info = document.createElement("span");

            const name = document.createElement("strong");
            name.textContent = tenant.full_name;

            const details = document.createElement("small");

            details.textContent = [
                tenant.phone,
                tenant.identity_number
            ].filter(Boolean).join(" • ");

            info.append(name, details);

            checkboxLabel.append(checkbox, info);

            const representativeLabel = document.createElement("label");
            representativeLabel.className = "contract-create-representative";

            const radio = document.createElement("input");

            radio.type = "radio";
            radio.name = "representative_tenant_id";
            radio.value = tenant.id;
            radio.disabled = true;

            const radioText = document.createElement("span");
            radioText.textContent = "Đại diện";

            representativeLabel.append(radio, radioText);

            item.append(checkboxLabel, representativeLabel);

            tenantList.appendChild(item);

            // Checkbox chọn người thuê
            checkbox.addEventListener("change", function () {

                radio.disabled = !this.checked;

                // Nếu bỏ chọn người đại diện thì bỏ radio
                if (!this.checked && radio.checked) {
                    radio.checked = false;
                }

                // Nếu chưa có người đại diện, chọn người vừa được tick
                if (this.checked) {

                    const currentRepresentative =
                        tenantList.querySelector(
                            'input[name="representative_tenant_id"]:checked'
                        );

                    if (!currentRepresentative) {
                        radio.checked = true;
                    }
                }

                clearFieldError("tenant_ids");
                clearFieldError("representative_tenant_id");
            });

            // Khi chọn người đại diện
            radio.addEventListener("change", function () {

                clearFieldError("representative_tenant_id");
            });
        });
    }


    // ========================================
    // 9. XÓA LỖI VALIDATION
    // ========================================

    function clearErrors() {

        form.querySelectorAll("[data-error]").forEach(element => {
            element.textContent = "";
        });
    }

    function clearFieldError(field) {

        const element = form.querySelector(
            `[data-error="${field}"]`
        );

        if (element) {
            element.textContent = "";
        }
    }

    function showValidationErrors(errors) {

        clearErrors();

        Object.entries(errors).forEach(([field, messages]) => {

            // tenant_ids.0 sẽ được gom về tenant_ids
            const rootField = field.split(".")[0];

            const element = form.querySelector(
                `[data-error="${rootField}"]`
            );

            if (element) {
                element.textContent = messages[0];
            }
        });
    }


    // ========================================
    // 10. SUBMIT FORM TẠO HỢP ĐỒNG
    // ========================================

    form.addEventListener("submit", async function (e) {

        e.preventDefault();

        clearErrors();

        submitButton.disabled = true;

        const originalButtonHTML = submitButton.innerHTML;

        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Đang xử lý...
        `;

        try {

            const formData = new FormData(form);

            const response = await fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
                credentials: "same-origin",
            });

            const result = await response.json();

            // Validation error
            if (response.status === 422) {

                showValidationErrors(result.errors || {});

                window.showToast(
                    "error",
                    "Vui lòng kiểm tra lại thông tin."
                );

                return;
            }

            if (!response.ok || !result.success) {

                throw new Error(
                    result.message || "Không thể tạo hợp đồng."
                );
            }

            // Thành công
            closeModal();

            window.showToast(
                "success",
                result.message || "Tạo hợp đồng thành công."
            );

            // Tải lại danh sách hợp đồng
            setTimeout(function () {
                window.location.reload();
            }, 1000);

        } catch (err) {

            console.error("Lỗi tạo hợp đồng:", err);

            window.showToast(
                "error",
                err.message || "Đã xảy ra lỗi khi tạo hợp đồng."
            );

        } finally {

            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonHTML;
        }
    });

});
