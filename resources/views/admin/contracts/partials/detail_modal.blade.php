
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
    document.addEventListener("DOMContentLoaded", function () {
    // 1. Lấy các phần tử trong modal
    const modal = document.getElementById("contractDetailModal");

    if (!modal) return;

    const loading = document.getElementById("contractDetailLoading");
    const error = document.getElementById("contractDetailError");
    const content = document.getElementById("contractDetailContent");

    // 2. Hàm mở modal
    function openModal() {
        modal.classList.add("active");
        document.body.style.overflow = "hidden";

        loading.hidden = false;
        error.hidden = true;
        content.hidden = true;
    }

    // 3. Hàm đóng modal
    function closeModal() {
        modal.classList.remove("active");
        document.body.style.overflow = "";
    }

    // 4. Định dạng ngày tháng
    function formatDate(value) {
        if (!value) return "Chưa xác định";

        const date = String(value).substring(0, 10);
        const [year, month, day] = date.split("-");

        if (!year || !month || !day) return "Chưa xác định";

        return `${day}/${month}/${year}`;
    }

    // 5. Định dạng tiền Việt Nam
    function formatMoney(value) {
        return new Intl.NumberFormat("vi-VN").format(Number(value || 0)) + " đ";
    }

    // 6. Gán nội dung vào phần tử HTML
    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value ?? "Chưa có thông tin";
        }
    }

    // 7. Hiển thị danh sách người thuê
    function renderTenants(tenants) {
        const tenantList = document.getElementById("detailTenantList");

        tenantList.replaceChildren();

        if (!tenants || tenants.length === 0) {
            tenantList.textContent = "Hợp đồng chưa có người thuê.";
            return;
        }

        tenants.forEach(function (tenant) {
            const item = document.createElement("div");
            item.className = "contract-tenant-item";

            const info = document.createElement("div");
            info.className = "contract-tenant-info";

            const avatar = document.createElement("div");
            avatar.className = "contract-tenant-avatar";
            avatar.textContent = (tenant.full_name || "?").charAt(0).toUpperCase();

            const details = document.createElement("div");

            const name = document.createElement("div");
            name.className = "contract-tenant-name";
            name.textContent = tenant.full_name || "Chưa có tên";

            const phone = document.createElement("div");
            phone.className = "contract-tenant-phone";
            phone.textContent = tenant.phone || "Chưa có số điện thoại";

            details.append(name, phone);
            info.append(avatar, details);
            item.append(info);

            if (tenant.pivot?.is_representative) {
                const representative = document.createElement("span");
                representative.className = "contract-tenant-representative";
                representative.textContent = "Đại diện hợp đồng";

                item.append(representative);
            }

            tenantList.append(item);
        });
    }

    // 8. Hiển thị dữ liệu hợp đồng
    function renderContract(contract) {
        const statusLabels = {
            draft: "Bản nháp",
            waiting_signature: "Chờ ký",
            active: "Đang hiệu lực",
            expired: "Đã hết hạn",
            terminated: "Đã chấm dứt",
        };

        const contractCode = `HĐ-${String(contract.id).padStart(5, "0")}`;

        setText("detailContractCode", contractCode);
        setText("detailCode", contractCode);
        setText("detailStatus", statusLabels[contract.status] || contract.status);

        setText("detailStartDate", formatDate(contract.start_date));
        setText("detailEndDate", formatDate(contract.end_date));

        setText(
            "detailSignedAt",
            contract.signed_at
                ? formatDate(contract.signed_at) + " " +
                  String(contract.signed_at).substring(11, 16)
                : "Chưa ký"
        );

        setText("detailBillingDay", `Ngày ${contract.billing_day || 1} hàng tháng`);

        // Thông tin phòng
        setText("detailRoomName", contract.room?.name || "Chưa có phòng");
        setText("detailFloor", contract.room?.floor || "Chưa xác định");
        setText("detailArea", contract.room?.area ? `${contract.room.area} m²` : "Chưa xác định");

        // Danh sách người thuê
        renderTenants(contract.tenants);

        // Thông tin tài chính
        setText("detailRentPrice", formatMoney(contract.rent_price));
        setText("detailDeposit", formatMoney(contract.deposit_amount));

        // Điều khoản
        setText("detailTerms", contract.terms || "Chưa có điều khoản.");

        // Tệp hợp đồng
        const files = document.getElementById("detailContractFiles");

        files.replaceChildren();

        if (contract.pdf_path || contract.signed_pdf_path) {
            const message = document.createElement("span");
            message.className = "text-muted";
            message.textContent = "Hợp đồng đã có tệp đính kèm.";

            files.append(message);
        } else {
            files.textContent = "Chưa có tệp đính kèm.";
        }
    }

    // 9. Gọi API lấy chi tiết hợp đồng
    async function viewContract(url) {
        openModal();

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

            if (!result.success || !result.data) {
                throw new Error("Dữ liệu hợp đồng không hợp lệ.");
            }

            renderContract(result.data);

            loading.hidden = true;
            content.hidden = false;
        } catch (err) {
            console.error("Lỗi tải chi tiết hợp đồng:", err);

            loading.hidden = true;
            error.hidden = false;
        }
    }

    // 10. Bắt sự kiện nhấn nút xem chi tiết
    document.addEventListener("click", function (event) {
        const button = event.target.closest(".btn-view-contract");

        if (!button) return;

        const url = button.dataset.url;

        if (url) {
            viewContract(url);
        }
    });

    // 11. Đóng modal khi nhấn nút đóng hoặc lớp nền
    modal.querySelectorAll("[data-close-detail]").forEach(function (button) {
        button.addEventListener("click", closeModal);
    });

    // Đóng modal bằng phím Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && modal.classList.contains("active")) {
            closeModal();
        }
    });
});
</script>