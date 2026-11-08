<div class="contract-edit-modal" id="contractEditModal">

    <div class="contract-edit-overlay" data-close-edit></div>

    <div class="contract-edit-content">

        {{-- Header --}}
        <div class="contract-edit-header">
            <div class="contract-edit-title">
                <div class="contract-edit-icon">
                    <i class="fa-solid fa-file-pen"></i>
                </div>

                <div>
                    <h5>Chỉnh sửa hợp đồng</h5>
                    <p id="editContractCode">Đang tải thông tin...</p>
                </div>
            </div>

            <button type="button"
                class="contract-edit-close"
                data-close-edit>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Form --}}
        <form id="editContractForm" method="POST">

            @csrf
            @method('PUT')

            <div class="contract-edit-body">

                <div id="contractEditLoading" class="contract-edit-loading">
                    <div class="spinner-border text-success" role="status"></div>
                    <p>Đang tải thông tin hợp đồng...</p>
                </div>

                <div id="contractEditError" class="contract-edit-error" hidden>
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <p>Không thể tải thông tin hợp đồng.</p>
                </div>

                <div id="contractEditContent" hidden>

                    {{-- Thông tin hợp đồng --}}
                    <section class="contract-edit-section">
                        <h6 class="contract-edit-section-title">
                            <i class="fa-solid fa-file-signature"></i>
                            Thông tin hợp đồng
                        </h6>

                        <div class="contract-edit-grid">

                            <div class="contract-edit-field">
                                <label>Mã hợp đồng</label>
                                <input type="text" id="editCode"
                                    class="form-control" disabled>
                            </div>

                            <div class="contract-edit-field">
                                <label>Phòng thuê</label>
                                <input type="text" id="editRoom"
                                    class="form-control" disabled>
                            </div>

                            <div class="contract-edit-field">
                                <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date"
                                    id="editStartDate" class="form-control">
                                <small class="text-danger" data-error="start_date"></small>
                            </div>

                            <div class="contract-edit-field">
                                <label>Ngày kết thúc</label>
                                <input type="date" name="end_date"
                                    id="editEndDate" class="form-control">
                                <small class="text-danger" data-error="end_date"></small>
                            </div>

                            <div class="contract-edit-field">
                                <label>Giá thuê hàng tháng <span class="text-danger">*</span></label>
                                <input type="number" name="rent_price"
                                    id="editRentPrice" class="form-control" min="0">
                                <small class="text-danger" data-error="rent_price"></small>
                            </div>

                            <div class="contract-edit-field">
                                <label>Tiền đặt cọc <span class="text-danger">*</span></label>
                                <input type="number" name="deposit_amount"
                                    id="editDeposit" class="form-control" min="0">
                                <small class="text-danger" data-error="deposit_amount"></small>
                            </div>

                            <div class="contract-edit-field">
                                <label>Ngày thanh toán hàng tháng <span class="text-danger">*</span></label>
                                <input type="number" name="billing_day"
                                    id="editBillingDay" class="form-control"
                                    min="1" max="31">
                                <small class="text-danger" data-error="billing_day"></small>
                            </div>

                        </div>
                    </section>

                    {{-- Người thuê --}}
                    <section class="contract-edit-section">
                        <h6 class="contract-edit-section-title">
                            <i class="fa-solid fa-users"></i>
                            Danh sách người thuê
                        </h6>

                        <p class="contract-edit-note">
                            Chọn những người thuê thuộc hợp đồng và chỉ định một người đại diện.
                        </p>

                        <div id="editTenantList" class="contract-edit-tenant-list">
                            {{-- JavaScript render danh sách người thuê --}}
                        </div>

                        <small class="text-danger" data-error="tenant_ids"></small>
                        <small class="text-danger" data-error="representative_tenant_id"></small>
                    </section>

                    {{-- Điều khoản --}}
                    <section class="contract-edit-section">
                        <h6 class="contract-edit-section-title">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Điều khoản hợp đồng
                        </h6>

                        <textarea name="terms" id="editTerms"
                            class="form-control" rows="5"
                            placeholder="Nhập điều khoản hợp đồng..."></textarea>

                        <small class="text-danger" data-error="terms"></small>
                    </section>

                </div>
            </div>

            {{-- Footer --}}
            <div class="contract-edit-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-close-edit>
                    Hủy
                </button>

                <button type="submit"
                    class="btn btn-success"
                    id="btnUpdateContract">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Lưu thay đổi
                </button>
            </div>

        </form>

    </div>
</div>