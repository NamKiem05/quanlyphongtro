
<div class="contract-detail-modal" id="contractDetailModal">

    <div class="contract-detail-overlay" data-close-detail></div>

    <div class="contract-detail-content">

        {{-- Header --}}
        <div class="contract-detail-header">
            <div class="contract-detail-title">
                <div class="contract-detail-icon">
                    <i class="fa-solid fa-file-contract"></i>
                </div>

                <div>
                    <h5>Chi tiết hợp đồng</h5>
                    <p id="detailContractCode">Đang tải thông tin...</p>
                </div>
            </div>

            <button type="button"
                class="contract-detail-close"
                data-close-detail>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="contract-detail-body">

            <div id="contractDetailLoading" class="contract-detail-loading">
                <div class="spinner-border text-success" role="status"></div>
                <p>Đang tải thông tin hợp đồng...</p>
            </div>

            <div id="contractDetailError" class="contract-detail-error" hidden>
                <i class="fa-solid fa-circle-exclamation"></i>
                <p>Không thể tải thông tin hợp đồng.</p>
            </div>

            <div id="contractDetailContent" hidden>

                {{-- Thông tin hợp đồng --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-file-signature"></i>
                        Thông tin hợp đồng
                    </h6>

                    <div class="contract-info-grid">
                        <div class="contract-info-item">
                            <span class="contract-info-label">Mã hợp đồng</span>
                            <span class="contract-info-value" id="detailCode">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Trạng thái</span>
                            <span id="detailStatus">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Ngày bắt đầu</span>
                            <span class="contract-info-value" id="detailStartDate">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Ngày kết thúc</span>
                            <span class="contract-info-value" id="detailEndDate">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Ngày ký</span>
                            <span class="contract-info-value" id="detailSignedAt">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Ngày thanh toán</span>
                            <span class="contract-info-value" id="detailBillingDay">—</span>
                        </div>
                    </div>
                </section>

                {{-- Thông tin phòng --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-door-open"></i>
                        Thông tin phòng
                    </h6>

                    <div class="contract-info-grid">
                        <div class="contract-info-item">
                            <span class="contract-info-label">Tên phòng</span>
                            <span class="contract-info-value" id="detailRoomName">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Tầng</span>
                            <span class="contract-info-value" id="detailFloor">—</span>
                        </div>

                        <div class="contract-info-item">
                            <span class="contract-info-label">Diện tích</span>
                            <span class="contract-info-value" id="detailArea">—</span>
                        </div>
                    </div>
                </section>

                {{-- Người thuê --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-users"></i>
                        Danh sách người thuê
                    </h6>

                    <div class="contract-tenant-list" id="detailTenantList">
                        {{-- JavaScript render người thuê --}}
                    </div>
                </section>

                {{-- Tài chính --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-wallet"></i>
                        Thông tin tài chính
                    </h6>

                    <div class="contract-info-grid">
                        <div class="contract-finance-box">
                            <div class="contract-finance-label">Giá thuê hàng tháng</div>
                            <div class="contract-finance-value" id="detailRentPrice">—</div>
                        </div>

                        <div class="contract-finance-box">
                            <div class="contract-finance-label">Tiền đặt cọc</div>
                            <div class="contract-finance-value" id="detailDeposit">—</div>
                        </div>
                    </div>
                </section>

                {{-- Điều khoản --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Điều khoản hợp đồng
                    </h6>

                    <div class="contract-terms" id="detailTerms">
                        Chưa có điều khoản.
                    </div>
                </section>

                {{-- File hợp đồng --}}
                <section class="contract-section">
                    <h6 class="contract-section-title">
                        <i class="fa-solid fa-paperclip"></i>
                        Tệp đính kèm
                    </h6>

                    <div id="detailContractFiles">
                        <span class="text-muted">Chưa có tệp đính kèm.</span>
                    </div>
                </section>

            </div>
        </div>

        {{-- Footer --}}
        <div class="contract-detail-footer">
            <button type="button"
                class="btn btn-secondary"
                data-close-detail>
                Đóng
            </button>
        </div>

    </div>
</div>
<script>

</script>