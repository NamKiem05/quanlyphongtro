document.addEventListener("DOMContentLoaded", function () {
    // 1. Lấy các phần tử Modal
    const modal = document.getElementById("contractEditModal");

    if (!modal) return;

    const form = document.getElementById("editContractForm");
    const loading = document.getElementById("contractEditLoading");
    const error = document.getElementById("contractEditError");
    const content = document.getElementById("contractEditContent");
    const tenantList = document.getElementById("editTenantList");

    // 2. Mở Modal
    function openModal() {
        modal.classList.add("active");
        document.body.style.overflow = "hidden";

        loading.hidden = false;
        error.hidden = true;
        content.hidden = true;
    }

    // 3. Đóng Modal
    function closeModal() {
        modal.classList.remove("active");
        document.body.style.overflow = "";
    }

    // 4. Gán giá trị vào input
    function setValue(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.value = value ?? "";
        }
    }

    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value ?? "";
        }
    }
    // 5. Xóa lỗi validation cũ
    function clearErrors() {
        modal.querySelectorAll("[data-error]").forEach(function (element) {
            element.textContent = "";
        });

        form.querySelectorAll(".is-invalid").forEach(function (element) {
            element.classList.remove("is-invalid");
        });
    }

    // 6. Hiển thị lỗi tải dữ liệu
    function showError() {
        loading.hidden = true;
        content.hidden = true;
        error.hidden = false;
    }

    // Hiển thị Toast bằng JavaScript
    

    // 7. Render danh sách người thuê
    function renderTenants(allTenants, contractTenants) {
        tenantList.replaceChildren();

        const selectedTenants = new Map(
            (contractTenants || []).map(function (tenant) {
                return [
                    String(tenant.id),
                    Number(tenant.pivot?.is_representative) === 1,
                ];
            }),
        );

        if (!allTenants || allTenants.length === 0) {
            tenantList.textContent = "Chưa có người thuê nào trong hệ thống.";
            return;
        }

        allTenants.forEach(function (tenant) {
            const tenantId = String(tenant.id);
            const isSelected = selectedTenants.has(tenantId);
            const isRepresentative = selectedTenants.get(tenantId) === true;

            const item = document.createElement("div");
            item.className = "contract-edit-tenant-item";

            // Checkbox chọn người thuê
            const checkboxLabel = document.createElement("label");
            checkboxLabel.className = "contract-edit-tenant-info";

            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.name = "tenant_ids[]";
            checkbox.value = tenant.id;
            checkbox.checked = isSelected;

            const details = document.createElement("div");
            details.className = "contract-edit-tenant-details";

            const name = document.createElement("div");
            name.className = "contract-edit-tenant-name";
            name.textContent = tenant.full_name || "Chưa có tên";

            const phone = document.createElement("div");
            phone.className = "contract-edit-tenant-phone";
            phone.textContent = tenant.phone || "Chưa có số điện thoại";

            details.append(name, phone);
            checkboxLabel.append(checkbox, details);

            // Radio chọn người đại diện
            const representativeLabel = document.createElement("label");
            representativeLabel.className = "contract-edit-representative";

            const radio = document.createElement("input");
            radio.type = "radio";
            radio.name = "representative_tenant_id";
            radio.value = tenant.id;
            radio.checked = isRepresentative;
            radio.disabled = !isSelected;

            const radioText = document.createElement("span");
            radioText.textContent = "Người đại diện";

            representativeLabel.append(radio, radioText);

            // Khi bỏ chọn người thuê thì vô hiệu hóa radio
            checkbox.addEventListener("change", function () {
                radio.disabled = !checkbox.checked;

                if (!checkbox.checked && radio.checked) {
                    radio.checked = false;
                }
            });

            item.append(checkboxLabel, representativeLabel);
            tenantList.append(item);
        });
    }

    // 8. Hiển thị dữ liệu hợp đồng vào Form
    function renderContract(contract, tenants) {
        const contractCode = `HĐ-${String(contract.id).padStart(5, "0")}`;

        setText("editContractCode", contractCode);
        setValue("editCode", contractCode);
        setValue("editRoom", contract.room?.name || "");

        setValue("editStartDate", contract.start_date);
        setValue("editEndDate", contract.end_date);

        setValue("editRentPrice", contract.rent_price);
        setValue("editDeposit", contract.deposit_amount);
        setValue("editBillingDay", contract.billing_day);

        setValue("editTerms", contract.terms);

        renderTenants(tenants, contract.tenants);

        clearErrors();
    }

    // 9. Gọi API lấy dữ liệu hợp đồng
    async function editContract(url, updateUrl) {
        openModal();
        clearErrors();

        form.action = updateUrl;

        try {
            const response = await fetch(url, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                throw new Error("Không thể tải dữ liệu hợp đồng.");
            }

            const result = await response.json();

            if (
                !result.success ||
                !result.data?.contract ||
                !result.data?.tenants
            ) {
                throw new Error("Dữ liệu hợp đồng không hợp lệ.");
            }

            renderContract(result.data.contract, result.data.tenants);

            loading.hidden = true;
            content.hidden = false;
        } catch (err) {
            console.error("Lỗi tải dữ liệu hợp đồng:", err);
            showError();
        }
    }

    // 10. Bắt sự kiện nhấn nút chỉnh sửa
    document.addEventListener("click", function (event) {
        const button = event.target.closest(".btn-edit-contract");

        if (!button) return;

        const url = button.dataset.url;
        const updateUrl = button.dataset.updateUrl;

        if (url && updateUrl) {
            editContract(url, updateUrl);
        }
    });

    // 11. Đóng Modal
    modal.querySelectorAll("[data-close-edit]").forEach(function (button) {
        button.addEventListener("click", closeModal);
    });

    // 12. Đóng Modal bằng phím Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && modal.classList.contains("active")) {
            closeModal();
        }
    });

    // 13. Gửi form cập nhật bằng AJAX
    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        clearErrors();

        const submitButton = document.getElementById("btnUpdateContract");
        submitButton.disabled = true;
        submitButton.textContent = "Đang lưu...";

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            // Lỗi validation
            if (response.status === 422) {
                Object.entries(result.errors || {}).forEach(
                    ([field, messages]) => {
                        const errorElement = modal.querySelector(
                            `[data-error="${field}"]`,
                        );

                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }

                        const input = form.querySelector(`[name="${field}"]`);

                        if (input) {
                            input.classList.add("is-invalid");
                        }
                    },
                );

                return;
            }

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || "Cập nhật hợp đồng thất bại.",
                );
            }

            showToast("success", result.message);
            closeModal();
            // Đợi Toast hiển thị rồi mới tải lại trang
            setTimeout(function () {
                window.location.reload();
            }, 3000);
        } catch (err) {
            console.error("Lỗi cập nhật hợp đồng:", err);
            showToast(
                "error",
                err.message || "Có lỗi xảy ra khi cập nhật hợp đồng.",
            );
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = "Lưu thay đổi";
        }
    });
});
