<style>

/* ========================================
   CONTRACT CREATE MODAL
   BLUE THEME
======================================== */

.contract-create-modal {
    position: fixed;
    inset: 0;
    z-index: 1055;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.contract-create-modal.active {
    display: flex;
}

/* ========================================
   OVERLAY
======================================== */

.contract-create-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(3px);
}

/* ========================================
   MODAL CONTENT
======================================== */

.contract-create-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 800px;
    max-height: 80vh;
    background: #ffffff;
    border-radius: 16px;
    overflow: auto;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
    animation: contractCreateShow 0.25s ease;
}

@keyframes contractCreateShow {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* ========================================
   HEADER
======================================== */

.contract-create-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 26px;
    color: #ffffff;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

.contract-create-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.contract-create-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.18);
    font-size: 21px;
}

.contract-create-title h5 {
    margin: 0 0 5px;
    font-size: 19px;
    font-weight: 700;
    color: #ffffff;
}

.contract-create-title p {
    margin: 0;
    font-size: 13px;
    color: #dbeafe;
}

.contract-create-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 9px;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.15);
    font-size: 17px;
    cursor: pointer;
    transition: 0.2s;
}

.contract-create-close:hover {
    background: rgba(255, 255, 255, 0.28);
    transform: rotate(90deg);
}

/* ========================================
   BODY
======================================== */

.contract-create-body {
    flex: 1;
    min-height: 0;
    padding: 24px 26px;
    overflow-y: auto;
    background: #f8fafc;
}

.contract-create-body::-webkit-scrollbar {
    width: 6px;
}

.contract-create-body::-webkit-scrollbar-thumb {
    background: #bfdbfe;
    border-radius: 10px;
}

/* ========================================
   SECTION
======================================== */

.contract-create-section {
    padding: 22px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    background: #ffffff;
}

.contract-create-section:last-child {
    margin-bottom: 0;
}

.contract-create-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #eaf0f7;
    color: #1e40af;
    font-size: 15px;
    font-weight: 700;
}

.contract-create-section-title i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 9px;
    color: #2563eb;
    background: #eff6ff;
    font-size: 14px;
}

/* ========================================
   FORM GRID
======================================== */

.contract-create-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.contract-create-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.contract-create-field label {
    margin: 0;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
}

.contract-create-field label .text-danger {
    color: #ef4444;
}

/* ========================================
   INPUT / SELECT / TEXTAREA
======================================== */

.contract-create-field .form-control,
.contract-create-field .form-select,
.contract-create-section textarea.form-control {
    width: 100%;
    min-height: 43px;
    padding: 10px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #1e293b;
    background-color: #ffffff;
    font-size: 13px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.contract-create-field .form-control:focus,
.contract-create-field .form-select:focus,
.contract-create-section textarea.form-control:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.13);
}

.contract-create-field .form-control:disabled {
    color: #64748b;
    background: #f1f5f9;
    cursor: not-allowed;
}

.contract-create-section textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

/* ========================================
   TENANT NOTE
======================================== */

.contract-create-note {
    margin: -5px 0 16px;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

/* ========================================
   TENANT LIST
======================================== */

.contract-create-tenant-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.contract-create-tenant-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    transition: 0.2s;
}

.contract-create-tenant-item:hover {
    border-color: #93c5fd;
    background: #f8fbff;
}

.contract-create-tenant-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
    cursor: pointer;
}

.contract-create-tenant-info input,
.contract-create-representative input {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    accent-color: #2563eb;
    cursor: pointer;
}

.contract-create-tenant-info span {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.contract-create-tenant-info strong {
    color: #1e293b;
    font-size: 13px;
    font-weight: 600;
}

.contract-create-tenant-info small {
    color: #64748b;
    font-size: 12px;
    overflow-wrap: anywhere;
}

.contract-create-representative {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
    color: #2563eb;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.contract-create-representative input:disabled {
    cursor: not-allowed;
    opacity: 0.4;
}

/* ========================================
   VALIDATION ERROR
======================================== */

.contract-create-field [data-error],
.contract-create-section [data-error] {
    min-height: 0;
    color: #dc2626;
    font-size: 12px;
}

.contract-create-field [data-error]:not(:empty),
.contract-create-section [data-error]:not(:empty) {
    margin-top: -3px;
}

/* ========================================
   LOADING
======================================== */

.contract-create-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    gap: 14px;
    color: #2563eb;
}

.contract-create-loading p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

/* ========================================
   ERROR LOAD DATA
======================================== */

.contract-create-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 260px;
    gap: 12px;
    color: #dc2626;
}

.contract-create-error i {
    font-size: 35px;
}

.contract-create-error p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

/* ========================================
   FOOTER
======================================== */

.contract-create-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 17px 26px;
    border-top: 1px solid #e2e8f0;
    background: #ffffff;
}

.contract-create-footer .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    min-height: 42px;
    padding: 9px 17px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.contract-create-footer .btn-success {
    border-color: #41a11b;
    background: #068b01;
    color: white;
}

.contract-create-footer .btn-success:hover {
    border-color: #1dd855;
    background: #23d81d;
    box-shadow: 0 4px 12px rgba(37, 235, 113, 0.2);
}

.contract-create-footer .btn-success:disabled {
    border-color: #93c5fd;
    background: #93c5fd;
    cursor: not-allowed;
}

.contract-create-footer .btn-secondary {
    border-color: #cbd5e1;
    color: #475569;
    background: #f1f5f9;
}

.contract-create-footer .btn-secondary:hover {
    background: #e2e8f0;
}

/* ========================================
   HIDDEN ATTRIBUTE
======================================== */

.contract-create-modal [hidden] {
    display: none !important;
}

/* ========================================
   RESPONSIVE
======================================== */

@media (max-width: 768px) {

    .contract-create-modal {
        padding: 10px;
    }

    .contract-create-content {
        max-height: 96vh;
        border-radius: 12px;
    }

    .contract-create-header {
        padding: 17px;
    }

    .contract-create-title h5 {
        font-size: 16px;
    }

    .contract-create-title p {
        font-size: 11px;
    }

    .contract-create-body {
        padding: 16px;
    }

    .contract-create-section {
        padding: 16px;
        margin-bottom: 14px;
    }

    .contract-create-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .contract-create-footer {
        padding: 14px 16px;
    }
}

@media (max-width: 480px) {

    .contract-create-icon {
        width: 40px;
        height: 40px;
        font-size: 17px;
    }

    .contract-create-tenant-item {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
    }

    .contract-create-representative {
        padding-left: 29px;
    }

    .contract-create-footer .btn {
        flex: 1;
        min-width: 0;
    }
}

</style>
<div class="contract-create-modal"
    id="contractCreateModal"
    data-create-data-url="{{ route('contracts.create-data') }}">

    <div class="contract-create-overlay" data-close-create></div>

    <div class="contract-create-content">

        {{-- Header --}}
        <div class="contract-create-header">
            <div class="contract-create-title">
                <div class="contract-create-icon">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>

                <div>
                    <h5>Tạo hợp đồng mới</h5>
                    <p>Nhập thông tin để tạo hợp đồng thuê phòng</p>
                </div>
            </div>

            <button type="button"
                class="contract-create-close"
                data-close-create>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Form --}}
        <form id="createContractForm"
            method="POST"
            action="{{ route('contracts.store') }}">

            @csrf

            <div class="contract-create-body">

                {{-- Loading --}}
                <div id="contractCreateLoading" class="contract-create-loading">
                    <div class="spinner-border text-success" role="status"></div>
                    <p>Đang tải dữ liệu...</p>
                </div>

                {{-- Error --}}
                <div id="contractCreateError" class="contract-create-error" hidden>
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <p>Không thể tải dữ liệu tạo hợp đồng.</p>
                </div>

                {{-- Content --}}
                <div id="contractCreateContent" hidden>

                    {{-- Thông tin phòng và hợp đồng --}}
                    <section class="contract-create-section">

                        <h6 class="contract-create-section-title">
                            <i class="fa-solid fa-file-signature"></i>
                            Thông tin hợp đồng
                        </h6>

                        <div class="contract-create-grid">

                            {{-- Mã hợp đồng --}}
                            <div class="contract-create-field">
                                <label>Mã hợp đồng</label>

                                <input type="text"
                                    class="form-control"
                                    value="Tự động tạo sau khi lưu"
                                    disabled>
                            </div>

                            {{-- Chọn phòng --}}
                            <div class="contract-create-field">
                                <label>
                                    Phòng thuê
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="room_id"
                                    id="createRoom"
                                    class="form-select">

                                    <option value="">-- Chọn phòng thuê --</option>

                                </select>

                                <small class="text-danger" data-error="room_id"></small>
                            </div>

                            {{-- Tầng --}}
                            <div class="contract-create-field">
                                <label>Tầng</label>

                                <input type="text"
                                    id="createRoomFloor"
                                    class="form-control"
                                    placeholder="Chưa chọn phòng"
                                    disabled>
                            </div>

                            {{-- Diện tích --}}
                            <div class="contract-create-field">
                                <label>Diện tích</label>

                                <input type="text"
                                    id="createRoomArea"
                                    class="form-control"
                                    placeholder="Chưa chọn phòng"
                                    disabled>
                            </div>

                            {{-- Ngày bắt đầu --}}
                            <div class="contract-create-field">
                                <label>
                                    Ngày bắt đầu
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                    name="start_date"
                                    id="createStartDate"
                                    class="form-control">

                                <small class="text-danger" data-error="start_date"></small>
                            </div>

                            {{-- Ngày kết thúc --}}
                            <div class="contract-create-field">
                                <label>Ngày kết thúc</label>

                                <input type="date"
                                    name="end_date"
                                    id="createEndDate"
                                    class="form-control">

                                <small class="text-danger" data-error="end_date"></small>
                            </div>

                            {{-- Giá thuê --}}
                            <div class="contract-create-field">
                                <label>
                                    Giá thuê hàng tháng
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                    name="rent_price"
                                    id="createRentPrice"
                                    class="form-control"
                                    min="0"
                                    placeholder="Nhập giá thuê">

                                <small class="text-danger" data-error="rent_price"></small>
                            </div>

                            {{-- Tiền cọc --}}
                            <div class="contract-create-field">
                                <label>
                                    Tiền đặt cọc
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                    name="deposit_amount"
                                    id="createDeposit"
                                    class="form-control"
                                    min="0"
                                    value="0">

                                <small class="text-danger" data-error="deposit_amount"></small>
                            </div>

                            {{-- Ngày thanh toán --}}
                            <div class="contract-create-field">
                                <label>
                                    Ngày thanh toán hàng tháng
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                    name="billing_day"
                                    id="createBillingDay"
                                    class="form-control"
                                    min="1"
                                    max="31"
                                    value="1">

                                <small class="text-danger" data-error="billing_day"></small>
                            </div>

                        </div>
                    </section>

                    {{-- Danh sách người thuê --}}
                    <section class="contract-create-section">

                        <h6 class="contract-create-section-title">
                            <i class="fa-solid fa-users"></i>
                            Danh sách người thuê
                        </h6>

                        <p class="contract-create-note">
                            Chọn những người thuê thuộc hợp đồng và chỉ định một người đại diện.
                        </p>

                        <div id="createTenantList"
                            class="contract-create-tenant-list">

                            {{-- JavaScript render danh sách người thuê --}}

                        </div>

                        <small class="text-danger" data-error="tenant_ids"></small>
                        <small class="text-danger" data-error="representative_tenant_id"></small>

                    </section>

                    {{-- Điều khoản --}}
                    <section class="contract-create-section">

                        <h6 class="contract-create-section-title">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Điều khoản hợp đồng
                        </h6>

                        <textarea name="terms"
                            id="createTerms"
                            class="form-control"
                            rows="5"
                            placeholder="Nhập điều khoản hợp đồng..."></textarea>

                        <small class="text-danger" data-error="terms"></small>

                    </section>

                </div>
            </div>

            {{-- Footer --}}
            <div class="contract-create-footer">

                <button type="button"
                    class="btn btn-secondary"
                    data-close-create>
                    Hủy
                </button>

                <button type="submit"
                    class="btn btn-success"
                    id="btnCreateContractSubmit">

                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Tạo hợp đồng

                </button>

            </div>

        </form>

    </div>
</div>