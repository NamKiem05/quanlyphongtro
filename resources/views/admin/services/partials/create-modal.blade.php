<style>
    /* =====================================================
   CREATE SERVICE MODAL - BLUE THEME
   ===================================================== */

    #createServiceModal {
        --service-blue-900: #12336b;
        --service-blue-800: #17449a;
        --service-blue-700: #1d4ed8;
        --service-blue-600: #2563eb;
        --service-blue-500: #3b82f6;
        --service-blue-100: #dbeafe;
        --service-blue-50: #eff6ff;
        --service-text: #172554;
        --service-muted: #64748b;
        --service-border: #dbe4f0;
        --service-danger: #dc2626;
        --service-white: #ffffff;
    }

    /* =====================================================
   MODAL OVERLAY
   ===================================================== */

    #createServiceModal {
        position: fixed;
        inset: 0;
        z-index: 1055;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    #createServiceModal.active {
        display: flex;
    }

    #createServiceModal .service-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(73, 76, 81, 0.58);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* =====================================================
   MODAL DIALOG
   ===================================================== */

    #createServiceModal .service-modal-dialog {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 650px;
        margin: auto;
        animation: serviceModalShow .25s ease;
    }

    #createServiceModal .service-modal-content {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-height: calc(100vh - 20vh);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 25px 70px rgba(15, 35, 75, 0.3);
    }

    @keyframes serviceModalShow {
        from {
            opacity: 0;
            transform: translateY(18px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* =====================================================
   HEADER
   ===================================================== */

    #createServiceModal .service-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 24px;
        color: #ffffff;
        background: linear-gradient(125deg,
                #2563eb 0%,
                #1d4ed8 48%,
                #12336b 100%);
    }

    #createServiceModal .modal-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
    }

    #createServiceModal .modal-title>i {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 12px;
        color: #ffffff;
        background: rgba(255, 255, 255, .16);
        font-size: 17px;
    }

    #createServiceModal .btn-close-service-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 11px;
        color: #ffffff;
        background: rgba(255, 255, 255, .12);
        font-size: 17px;
        cursor: pointer;
        transition: .2s ease;
    }

    #createServiceModal .btn-close-service-icon:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, .25);
        transform: rotate(90deg);
    }

    /* =====================================================
   FORM
   ===================================================== */

    #createServiceModal .create-service-form {
        display: flex;
        flex-direction: column;
        min-height: 0;
        margin: 0;
    }

    #createServiceModal .service-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        padding: 24px;
        overflow-y: auto;
        background: #f5f8ff;
    }

    #createServiceModal .service-modal-body::-webkit-scrollbar {
        width: 6px;
    }

    #createServiceModal .service-modal-body::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background: #bfdbfe;
    }

    /* =====================================================
   ERROR ALERT
   ===================================================== */

    #createServiceModal .alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        padding: 13px 15px;
        border: 1px solid #fecaca;
        border-radius: 11px;
        color: #991b1b;
        background: #fef2f2;
        font-size: 13.5px;
    }

    #createServiceModal .alert.d-none {
        display: none !important;
    }

    /* =====================================================
   SECTION
   ===================================================== */

    #createServiceModal .detail-section {
        padding: 21px;
        border: 1px solid #e2eaf7;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 3px 12px rgba(30, 64, 175, .035);
    }

    #createServiceModal .detail-section-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e8effb;
    }

    #createServiceModal .detail-section-header>i {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: #2563eb;
        background: #dbeafe;
        font-size: 15px;
    }

    #createServiceModal .detail-section-header h6 {
        margin: 0;
        color: #12336b;
        font-size: 15px;
        font-weight: 700;
    }

    /* =====================================================
   FORM GRID
   ===================================================== */

    #createServiceModal .create-service-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 16px;
    }

    #createServiceModal .form-group {
        min-width: 0;
    }

    #createServiceModal .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* LABEL */

    #createServiceModal .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #243b64;
        font-size: 13.5px;
        font-weight: 650;
    }

    #createServiceModal .form-group label>i {
        width: 17px;
        color: #3b82f6;
        font-size: 13px;
        text-align: center;
    }

    #createServiceModal .required {
        color: #dc2626;
        font-weight: 700;
    }

    /* INPUT */

    #createServiceModal .form-control,
    #createServiceModal .form-select {
        width: 100%;
        height: 45px;
        padding: 0 13px;
        border: 1px solid #d2dced;
        border-radius: 10px;
        color: #172554;
        background-color: #ffffff;
        font-size: 13.5px;
        transition: border-color .2s, box-shadow .2s;
    }

    #createServiceModal .form-control::placeholder {
        color: #9aa9bd;
    }

    #createServiceModal .form-control:hover,
    #createServiceModal .form-select:hover {
        border-color: #93b4f8;
    }

    #createServiceModal .form-control:focus,
    #createServiceModal .form-select:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .14);
    }

    #createServiceModal textarea.form-control {
        height: auto;
        min-height: 95px;
        padding: 12px 13px;
        line-height: 1.55;
        resize: vertical;
    }

    /* =====================================================
   PRICE INPUT
   ===================================================== */

    #createServiceModal .input-with-unit {
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    #createServiceModal .input-with-unit .form-control {
        flex: 1;
        min-width: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        font-variant-numeric: tabular-nums;
    }

    #createServiceModal .input-unit {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 65px;
        padding: 0 13px;
        border: 1px solid #d2dced;
        border-left: 0;
        border-radius: 0 10px 10px 0;
        color: #1d4ed8;
        background: #eff6ff;
        font-size: 13px;
        font-weight: 700;
    }

    /* =====================================================
   VALIDATION
   ===================================================== */

    #createServiceModal .form-control.is-invalid,
    #createServiceModal .form-select.is-invalid {
        border-color: #dc2626;
        background-image: none;
    }

    #createServiceModal .form-control.is-invalid:focus,
    #createServiceModal .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .12);
    }

    #createServiceModal .field-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 500;
    }

    #createServiceModal .field-error:empty {
        display: none;
    }

    #createServiceModal .form-hint {
        display: block;
        margin-top: 7px;
        color: #718096;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =====================================================
   FOOTER
   ===================================================== */

    #createServiceModal .service-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 11px;
        padding: 16px 24px;
        border-top: 1px solid #e5eaf2;
        background: #ffffff;
    }

    #createServiceModal .footer-note {
        margin-right: auto;
        color: #718096;
        font-size: 12px;
    }

    #createServiceModal .btn-cancel-service,
    #createServiceModal .btn-submit-service {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 125px;
        height: 43px;
        padding: 0 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 650;
        cursor: pointer;
        transition: all .2s ease;
    }

    /* CANCEL */

    #createServiceModal .btn-cancel-service {
        border: 1px solid #dbe3ef;
        color: #52627a;
        background: #ffffff;
    }

    #createServiceModal .btn-cancel-service:hover {
        border-color: #bfdbfe;
        color: #1d4ed8;
        background: #eff6ff;
    }

    /* SUBMIT */

    #createServiceModal .btn-submit-service {
        border: 1px solid #2563eb;
        color: #ffffff;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        box-shadow: 0 4px 10px rgba(37, 99, 235, .2);
    }

    #createServiceModal .btn-submit-service:hover {
        border-color: #1e40af;
        background: linear-gradient(135deg, #2563eb, #12336b);
        box-shadow: 0 6px 15px rgba(37, 99, 235, .28);
        transform: translateY(-1px);
    }

    #createServiceModal .btn-submit-service:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* FOCUS */

    #createServiceModal .btn-cancel-service:focus-visible,
    #createServiceModal .btn-submit-service:focus-visible,
    #createServiceModal .btn-close-service-icon:focus-visible {
        outline: 3px solid rgba(59, 130, 246, .3);
        outline-offset: 3px;
    }

    /* =====================================================
   BODY SCROLL
   ===================================================== */

    body.service-modal-open {
        overflow: hidden;
    }

    /* =====================================================
   RESPONSIVE
   ===================================================== */

    @media (max-width: 575.98px) {

        #createServiceModal {
            padding: 10px;
        }

        #createServiceModal .service-modal-content {
            max-height: calc(100vh - 20vh);
            border-radius: 14px;
        }

        #createServiceModal .service-modal-header {
            padding: 16px;
        }

        #createServiceModal .service-modal-body {
            padding: 15px;
        }

        #createServiceModal .detail-section {
            padding: 15px;
        }

        #createServiceModal .create-service-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        #createServiceModal .form-group.full-width {
            grid-column: auto;
        }

        #createServiceModal .service-modal-footer {
            padding: 13px 15px;
            gap: 8px;
        }

        #createServiceModal .footer-note {
            display: none;
        }

        #createServiceModal .btn-cancel-service,
        #createServiceModal .btn-submit-service {
            flex: 1;
            min-width: 0;
            padding: 0 10px;
        }
    }
</style>

<div class="service-modal" id="createServiceModal" role="dialog" aria-modal="true" aria-labelledby="createServiceTitle">

    <div class="service-modal-overlay" data-create-service-close></div>

    <div class="service-modal-dialog create-service-dialog">
        <div class="service-modal-content">

            {{-- Header --}}
            <div class="service-modal-header">
                <h5 class="modal-title" id="createServiceTitle">
                    <i class="fa-solid fa-circle-plus"></i>
                    Thêm dịch vụ mới
                </h5>

                <button type="button"
                    class="btn-close-service-icon"
                    data-create-service-close
                    aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="createServiceForm"
                class="create-service-form"
                data-url="{{ route('services.store') }}"
                novalidate>
                @csrf

                <div class="service-modal-body">

                    <div id="createServiceError"
                        class="alert alert-danger d-none"
                        role="alert"></div>

                    <div class="detail-section create-service-section">

                        <div class="detail-section-header">
                            <i class="fa-solid fa-gears"></i>
                            <h6>Thông tin dịch vụ</h6>
                        </div>

                        <div class="create-service-form-grid">

                            {{-- Tên dịch vụ --}}
                            <div class="form-group full-width">
                                <label for="serviceName">
                                    <i class="fa-solid fa-tag"></i>
                                    Tên dịch vụ <span class="required">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    id="serviceName"
                                    class="form-control"
                                    maxlength="100"
                                    placeholder="Ví dụ: Điện, Nước, Internet..."
                                    autocomplete="off"
                                    required>

                                <small class="field-error" data-error="name"></small>
                            </div>

                            {{-- Loại dịch vụ --}}
                            <div class="form-group">
                                <label for="serviceType">
                                    <i class="fa-solid fa-list"></i>
                                    Loại dịch vụ <span class="required">*</span>
                                </label>

                                <select name="type"
                                    id="serviceType"
                                    class="form-select"
                                    required>
                                    <option value="metered">Tính theo chỉ số</option>
                                    <option value="fixed">Cố định</option>
                                </select>

                                <small class="field-error" data-error="type"></small>

                                <small class="form-hint">
                                    Theo chỉ số: tính theo mức dùng (điện, nước).
                                    Cố định: một mức giá mỗi tháng.
                                </small>
                            </div>

                            {{-- Đơn vị --}}
                            <div class="form-group">
                                <label for="serviceUnit">
                                    <i class="fa-solid fa-ruler"></i>
                                    Đơn vị tính
                                </label>

                                <input type="text"
                                    name="unit"
                                    id="serviceUnit"
                                    class="form-control"
                                    maxlength="30"
                                    placeholder="Ví dụ: kWh, m³, tháng...">

                                <small class="field-error" data-error="unit"></small>
                            </div>

                            {{-- Đơn giá --}}
                            <div class="form-group full-width">
                                <label for="servicePrice">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    Đơn giá <span class="required">*</span>
                                </label>

                                <div class="input-with-unit">
                                    <input type="number"
                                        name="unit_price"
                                        id="servicePrice"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        inputmode="decimal"
                                        placeholder="Ví dụ: 3500"
                                        required>

                                    <span class="input-unit">VNĐ</span>
                                </div>

                                <small class="field-error" data-error="unit_price"></small>

                                <small class="form-hint">
                                    Giá áp dụng mặc định khi thêm dịch vụ vào phòng.
                                </small>
                            </div>

                            {{-- Mô tả --}}
                            <div class="form-group full-width">
                                <label for="serviceDescription">
                                    <i class="fa-solid fa-file-lines"></i>
                                    Mô tả
                                </label>

                                <textarea name="description"
                                    id="serviceDescription"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Nhập mô tả về dịch vụ..."></textarea>

                                <small class="field-error" data-error="description"></small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="service-modal-footer">

                    <span class="footer-note">
                        <span class="required">*</span> Trường bắt buộc
                    </span>

                    <button type="button"
                        class="btn-cancel-service"
                        data-create-service-close>
                        <i class="fa-solid fa-xmark"></i>
                        Hủy
                    </button>

                    <button type="submit"
                        class="btn-submit-service"
                        id="btnSubmitService">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm dịch vụ</span>
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ========================================
        // 1. LẤY CÁC PHẦN TỬ HTML
        // ========================================

        const createModal = document.getElementById("createServiceModal");
        const createForm = document.getElementById("createServiceForm");
        const openCreateButton = document.getElementById("openAddServiceModal");
        const submitButton = document.getElementById("btnSubmitService");
        const createError = document.getElementById("createServiceError");
        const nameInput = document.getElementById("serviceName");

        if (!createModal || !createForm || !submitButton || !createError) {
            return;
        }

        // Giữ nguyên nội dung gốc của nút để khôi phục sau khi gửi
        const submitButtonHtml = submitButton.innerHTML;

        // Thời gian chờ trước khi tải lại danh sách (ms)
        const RELOAD_DELAY = 1500;

        // Phần tử đang được focus trước khi mở modal
        let lastFocusedElement = null;


        // ========================================
        // 2. HÀM TIỆN ÍCH
        // ========================================

        function hideCreateError() {
            createError.textContent = "";
            createError.classList.add("d-none");
        }

        function showCreateError(message) {
            createError.textContent = message;
            createError.classList.remove("d-none");
        }

        function clearCreateServiceErrors() {
            // Chỉ cần xóa nội dung, CSS :empty sẽ tự ẩn
            createForm.querySelectorAll(".field-error").forEach((element) => {
                element.textContent = "";
            });

            createForm.querySelectorAll(".is-invalid").forEach((element) => {
                element.classList.remove("is-invalid");
            });
        }

        function showCreateServiceErrors(errors) {
            clearCreateServiceErrors();

            Object.entries(errors).forEach(([field, messages]) => {
                const input = createForm.querySelector(`[name="${field}"]`);
                const errorElement = createForm.querySelector(`[data-error="${field}"]`);

                if (input) {
                    input.classList.add("is-invalid");
                }

                if (errorElement) {
                    errorElement.textContent = Array.isArray(messages) ? messages[0] : messages;
                }
            });

            // Đưa con trỏ tới trường lỗi đầu tiên
            createForm.querySelector(".is-invalid")?.focus();
        }


        // ========================================
        // 3. MỞ / ĐÓNG MODAL
        // ========================================

        function openCreateServiceModal() {
            lastFocusedElement = document.activeElement;

            createForm.reset();
            clearCreateServiceErrors();
            hideCreateError();

            createModal.classList.add("active");
            document.body.classList.add("service-modal-open");

            // Focus vào ô đầu tiên để nhập ngay
            setTimeout(() => nameInput?.focus(), 50);
        }

        function closeCreateServiceModal() {
            createModal.classList.remove("active");
            document.body.classList.remove("service-modal-open");

            // Trả focus về nút đã mở modal
            lastFocusedElement?.focus?.();
        }

        openCreateButton?.addEventListener("click", openCreateServiceModal);

        createModal
            .querySelectorAll("[data-create-service-close]")
            .forEach((button) => {
                button.addEventListener("click", closeCreateServiceModal);
            });

        // Đóng bằng phím ESC
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape" && createModal.classList.contains("active")) {
                closeCreateServiceModal();
            }
        });

        // Gõ lại vào trường nào thì xóa lỗi của trường đó
        createForm.addEventListener("input", function(event) {
            const field = event.target;

            if (!field.classList?.contains("is-invalid")) return;

            field.classList.remove("is-invalid");

            const errorElement = createForm.querySelector(`[data-error="${field.name}"]`);
            if (errorElement) errorElement.textContent = "";
        });


        // ========================================
        // 4. GỬI FORM THÊM DỊCH VỤ
        // ========================================

        createForm.addEventListener("submit", async function(event) {
            event.preventDefault();

            // Không cho gửi nhiều lần
            if (submitButton.disabled) return;

            clearCreateServiceErrors();
            hideCreateError();

            const url = createForm.dataset.url;

            if (!url) {
                showCreateError("Không tìm thấy đường dẫn thêm dịch vụ.");
                return;
            }

            // Trạng thái đang xử lý
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span>Đang xử lý...</span>
            `;

            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: new FormData(createForm),
                });

                // Đọc JSON an toàn: server lỗi có thể trả về HTML
                let result = {};
                try {
                    result = await response.json();
                } catch (parseError) {
                    result = {};
                }

                // Lỗi validation
                if (response.status === 422) {
                    showCreateServiceErrors(result.errors || {});
                    return;
                }

                // Phiên đăng nhập / CSRF hết hạn
                if (response.status === 419) {
                    throw new Error("Phiên làm việc đã hết hạn, vui lòng tải lại trang.");
                }

                if (!response.ok) {
                    throw new Error(result.message || `Không thể thêm dịch vụ (mã lỗi ${response.status}).`);
                }

                if (!result.success) {
                    throw new Error(result.message || "Thêm dịch vụ thất bại.");
                }

                // Thành công
                closeCreateServiceModal();

                if (typeof window.showToast === "function") {
                    window.showToast("success", result.message);
                } else {
                    alert(result.message || "Thêm dịch vụ thành công.");
                }

                setTimeout(() => window.location.reload(), RELOAD_DELAY);

            } catch (error) {
                console.error("Lỗi thêm dịch vụ:", error);
                showCreateError(error.message || "Đã xảy ra lỗi, vui lòng thử lại.");

            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = submitButtonHtml;
            }
        });

    });
</script>