
<div class="room-modal" id="roomDetailModal">

    {{-- Lớp nền --}}
    <div class="room-modal-overlay" data-room-close></div>

    {{-- Khung modal --}}
    <div class="room-modal-dialog">
        <div class="room-modal-content">

            {{-- Header --}}
            <div class="room-modal-header">
                <div class="modal-heading">
                    <div class="modal-heading-icon">
                        <i class="fa-solid fa-house"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="roomDetailModalLabel">
                            Chi tiết phòng
                        </h5>
                        <p class="modal-subtitle">
                            Thông tin phòng, người thuê và hợp đồng
                        </p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="room-modal-body">

                {{-- Loading --}}
                <div id="roomDetailLoading" class="text-center py-5">
                    <div class="spinner-border text-success"></div>
                    <p class="mt-3 mb-0 text-muted">
                        Đang tải thông tin...
                    </p>
                </div>

                {{-- Nội dung --}}
                <div id="roomDetailContent" class="d-none">

                    {{-- Thông tin phòng --}}
                    <div class="detail-section">

                        <div class="detail-section-header">
                            <i class="fa-solid fa-circle-info"></i>
                            <h6>Thông tin phòng</h6>
                        </div>

                        <div class="room-info-grid">

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>
                                <div>
                                    <span>Mã phòng</span>
                                    <strong id="detailRoomName">—</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-stairs"></i>
                                </div>
                                <div>
                                    <span>Tầng</span>
                                    <strong id="detailRoomFloor">—</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-expand"></i>
                                </div>
                                <div>
                                    <span>Diện tích</span>
                                    <strong id="detailRoomArea">—</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <span>Giá thuê</span>
                                    <strong id="detailRoomPrice" class="text-price">
                                        —
                                    </strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div>
                                    <span>Trạng thái</span>
                                    <strong id="detailRoomStatus">—</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <div>
                                    <span>Ngày tạo</span>
                                    <strong id="detailRoomCreated">—</strong>
                                </div>
                            </div>

                            <div class="info-item info-description">
                                <div class="info-icon">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div>
                                    <span>Mô tả</span>
                                    <p id="detailRoomDescription" class="mb-0">
                                        —
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Người thuê --}}
                    <div class="detail-section">

                        <div class="detail-section-header">
                            <i class="fa-solid fa-users"></i>
                            <h6>Danh sách người thuê</h6>
                        </div>

                        <div class="detail-section-body" id="detailTenants">
                            <div class="empty-state">
                                <i class="fa-solid fa-users"></i>
                                <p>Hiện tại phòng chưa có người thuê.</p>
                            </div>
                        </div>

                    </div>

                    {{-- Lịch sử hợp đồng --}}
                    <div class="detail-section">

                        <div class="detail-section-header">
                            <i class="fa-solid fa-file-contract"></i>
                            <h6>Lịch sử hợp đồng</h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table room-contract-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Mã HĐ</th>
                                        <th>Người thuê</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Tiền cọc</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>

                                <tbody id="detailContracts">
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fa-solid fa-file-contract"></i>
                                                <p>Chưa có hợp đồng.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                {{-- Lỗi --}}
                <div id="roomDetailError"
                     class="alert alert-danger d-none">
                </div>

            </div>

            {{-- Footer --}}
            <div class="room-modal-footer">
                <button type="button"
                        class="btn-close-room"
                        id="closeRoomModal">
                    <i class="fa-solid fa-xmark"></i>
                    Đóng
                </button>
            </div>

        </div>
    </div>
</div>
