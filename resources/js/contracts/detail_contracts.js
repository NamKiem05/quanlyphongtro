document.addEventListener("DOMContentLoaded", function () {
    // 1. Lấy các phần tử trong modal
    const modal = document.getElementById("contractDetailModal");

    if (!modal) return;

    const loading = document.getElementById("contractDetailLoading");
    const error = document.getElementById("contractDetailError");
    const content = document.getElementById("contractDetailContent");
    let currentDetailUrl = null;
    let currentOwnerSignUrl = null;

    let signatureContext = null;
    let hasOwnerSignature = false;

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
            avatar.textContent = (tenant.full_name || "?")
                .charAt(0)
                .toUpperCase();

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
            waiting_signature: "Chờ người thuê ký",
            waiting_owner_signature: "Chờ chủ nhà ký",
            active: "Đang hiệu lực",
            expired: "Đã hết hạn",
            terminated: "Đã chấm dứt",
        };

        const contractCode = `HĐ-${String(contract.id).padStart(5, "0")}`;

        setText("detailContractCode", contractCode);
        setText("detailCode", contractCode);
        setText(
            "detailStatus",
            statusLabels[contract.status] || contract.status,
        );

        setText("detailStartDate", formatDate(contract.start_date));
        setText("detailEndDate", formatDate(contract.end_date));

        setText(
            "detailSignedAt",
            contract.signed_at
                ? formatDate(contract.signed_at) +
                      " " +
                      String(contract.signed_at).substring(11, 16)
                : "Chưa ký",
        );
        setText(
            "detailOwnerSignedAt",
            contract.owner_signed_at
                ? formatDate(contract.owner_signed_at) +
                      " " +
                      String(contract.owner_signed_at).substring(11, 16)
                : "Chưa ký",
        );

        setText(
            "detailBillingDay",
            `Ngày ${contract.billing_day || 1} hàng tháng`,
        );

        // Thông tin phòng
        setText("detailRoomName", contract.room?.name || "Chưa có phòng");
        setText("detailFloor", contract.room?.floor || "Chưa xác định");
        setText(
            "detailArea",
            contract.room?.area ? `${contract.room.area} m²` : "Chưa xác định",
        );

        // Danh sách người thuê
        renderTenants(contract.tenants);

        // Thông tin tài chính
        setText("detailRentPrice", formatMoney(contract.rent_price));
        setText("detailDeposit", formatMoney(contract.deposit_amount));

        // Điều khoản
        setText("detailTerms", contract.terms || "Chưa có điều khoản.");

        // Tệp hợp đồng
        // Tệp hợp đồng
        const files = document.getElementById("detailContractFiles");

        files.replaceChildren();

        function createPdfLink(url, label, iconClass) {
            if (!url) return;

            const link = document.createElement("a");

            link.href = url;
            link.target = "_blank";
            link.rel = "noopener noreferrer";
            link.className = "btn btn-outline-primary btn-sm me-2 mb-2";

            const icon = document.createElement("i");
            icon.className = `${iconClass} me-2`;

            link.append(icon, document.createTextNode(label));

            files.append(link);
        }

        // PDF hợp đồng chưa ký
        if (contract.pdf_url) {
            createPdfLink(
                contract.pdf_url,
                "Xem hợp đồng",
                "fa-solid fa-file-pdf",
            );
        }

        // PDF hợp đồng đã ký
        if (contract.signed_pdf_url) {
            createPdfLink(
                contract.signed_pdf_url,
                "Xem hợp đồng đã ký",
                "fa-solid fa-file-circle-check",
            );
        }

        // Không có file
        if (!contract.pdf_url && !contract.signed_pdf_url) {
            const message = document.createElement("span");
            message.className = "text-muted";

            message.textContent =
                contract.status === "waiting_signature"
                    ? "Hợp đồng đang chờ người thuê ký."
                    : "Chưa có tệp hợp đồng.";

            files.append(message);
        }
        const ownerSignatureSection = document.getElementById(
            "ownerSignatureSection",
        );

        if (ownerSignatureSection) {
            ownerSignatureSection.hidden =
                contract.status !== "waiting_owner_signature";
        }
    }

    function initOwnerSignatureCanvas() {
        const canvas = document.getElementById("ownerSignatureCanvas");

        if (!canvas) return;

        const rect = canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;

        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;

        const ctx = canvas.getContext("2d");

        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        ctx.strokeStyle = "#111827";

        signatureContext = ctx;
        hasOwnerSignature = false;

        let isDrawing = false;

        function getPosition(event) {
            const bounds = canvas.getBoundingClientRect();

            return {
                x: event.clientX - bounds.left,
                y: event.clientY - bounds.top,
            };
        }

        canvas.onpointerdown = function (event) {
            isDrawing = true;
            canvas.setPointerCapture(event.pointerId);

            const pos = getPosition(event);

            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        };

        canvas.onpointermove = function (event) {
            if (!isDrawing) return;

            const pos = getPosition(event);

            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();

            hasOwnerSignature = true;
        };

        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
        }

        canvas.onpointerup = stopDrawing;
        canvas.onpointercancel = stopDrawing;
        canvas.onlostpointercapture = stopDrawing;
    }
    document
        .getElementById("btnClearOwnerSignature")
        ?.addEventListener("click", function () {
            const canvas = document.getElementById("ownerSignatureCanvas");

            if (!canvas || !signatureContext) return;

            signatureContext.clearRect(
                0,
                0,
                canvas.width / (window.devicePixelRatio || 1),
                canvas.height / (window.devicePixelRatio || 1),
            );

            hasOwnerSignature = false;

            hasOwnerSignature = false;
        });

    document
        .getElementById("btnSubmitOwnerSignature")
        ?.addEventListener("click", async function () {
            const canvas = document.getElementById("ownerSignatureCanvas");
            const button = this;

            if (!hasOwnerSignature) {
                alert("Anh vui lòng ký tên trước khi xác nhận.");
                return;
            }

            if (!currentOwnerSignUrl) {
                alert("Không tìm thấy đường dẫn ký hợp đồng.");
                return;
            }

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]',
            )?.content;

            if (!csrfToken) {
                alert("Không tìm thấy CSRF token.");
                return;
            }

            const signature = canvas.toDataURL("image/png");

            button.disabled = true;
            button.innerHTML = "Đang xử lý...";

            try {
                const response = await fetch(currentOwnerSignUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        signature: signature,
                    }),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const message =
                        result.message ||
                        result.errors?.signature?.[0] ||
                        result.errors?.contract?.[0] ||
                        "Không thể ký hợp đồng.";

                    throw new Error(message);
                }

                alert(result.message);

                // Tải lại thông tin hợp đồng sau khi ký thành công.
                await viewContract(currentDetailUrl);
            } catch (error) {
                console.error("Owner signing error:", error);
                alert(error.message);
            } finally {
                button.disabled = false;
                button.innerHTML =
                    '<i class="fa-solid fa-check"></i> Xác nhận ký hợp đồng';
            }
        });
    // 9. Gọi API lấy chi tiết hợp đồng
    async function viewContract(url, ownerSignUrl = null) {
        currentDetailUrl = url;
        currentOwnerSignUrl = ownerSignUrl;

        openModal();

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                throw new Error("Không thể tải hợp đồng.");
            }

            const result = await response.json();

            if (!result.success || !result.data) {
                throw new Error("Dữ liệu hợp đồng không hợp lệ.");
            }

            renderContract(result.data);

            loading.hidden = true;
            content.hidden = false;

            if (result.data.status === "waiting_owner_signature") {
                requestAnimationFrame(() => {
                    initOwnerSignatureCanvas();
                });
            }
        } catch (err) {
            console.error("View contract error:", err);

            loading.hidden = true;
            error.hidden = false;
        }
    }

    // 10. Bắt sự kiện nhấn nút xem chi tiết
    document.addEventListener("click", function (event) {
        // Nút ký hợp đồng
        const signButton = event.target.closest(".btn-owner-sign-contract");

        if (signButton) {
            const detailUrl = signButton.dataset.detailUrl;
            const signUrl = signButton.dataset.url;

            if (detailUrl && signUrl) {
                viewContract(detailUrl, signUrl);
            }

            return;
        }

        // Nút xem chi tiết
        const viewButton = event.target.closest(".btn-view-contract");

        if (!viewButton) return;

        const url = viewButton.dataset.url;

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
