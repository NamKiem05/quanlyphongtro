<div class="room-modal" id="editRoomModal">

    <div class="room-modal-overlay" data-edit-room-close></div>

    <div class="room-modal-dialog create-room-dialog">
        <div class="room-modal-content">

            {{-- Header --}}
            <div class="room-modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Chỉnh sửa thông tin phòng
                </h5>

                <button type="button"
                        class="btn-close-room-icon"
                        data-edit-room-close
                        aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="editRoomForm"
                  class="create-room-form"
                  method="POST"
                  novalidate>

                @csrf
                @method('PUT')

                <div class="room-modal-body">

                    <div id="editRoomError"
                         class="alert alert-danger d-none"></div>

                    <div class="detail-section create-room-section">

                        <div class="detail-section-header">
                            <i class="fa-solid fa-building"></i>
                            <h6>Thông tin phòng</h6>
                        </div>

                        <div class="create-room-form-grid">

                            {{-- Tên phòng --}}
                            <div class="form-group">
                                <label for="editRoomName">
                                    <i class="fa-solid fa-bed"></i>
                                    Tên phòng <span class="required">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       id="editRoomName"
                                       class="form-control"
                                       placeholder="Ví dụ: P101"
                                       autocomplete="off">

                                <small class="field-error"
                                       data-error="name"></small>
                            </div>

                            {{-- Tầng --}}
                            <div class="form-group">
                                <label for="editRoomFloor">
                                    <i class="fa-solid fa-stairs"></i>
                                    Tầng
                                </label>

                                <input type="number"
                                       name="floor"
                                       id="editRoomFloor"
                                       class="form-control"
                                       min="1"
                                       placeholder="Ví dụ: 1">

                                <small class="field-error"
                                       data-error="floor"></small>
                            </div>

                            {{-- Diện tích --}}
                            <div class="form-group">
                                <label for="editRoomArea">
                                    <i class="fa-solid fa-expand"></i>
                                    Diện tích
                                </label>

                                <div class="input-with-unit">
                                    <input type="number"
                                           name="area"
                                           id="editRoomArea"
                                           class="form-control"
                                           min="0"
                                           step="0.01">

                                    <span class="input-unit">m²</span>
                                </div>

                                <small class="field-error"
                                       data-error="area"></small>
                            </div>

                            {{-- Giá thuê --}}
                            <div class="form-group">
                                <label for="editRoomPrice">
                                    <i class="fa-solid fa-sack-dollar"></i>
                                    Giá thuê <span class="required">*</span>
                                </label>

                                <div class="input-with-unit">
                                    <input type="number"
                                           name="rent_price"
                                           id="editRoomPrice"
                                           class="form-control"
                                           min="0"
                                           step="1000">

                                    <span class="input-unit">VNĐ</span>
                                </div>

                                <small class="field-error"
                                       data-error="rent_price"></small>
                            </div>

                            {{-- Trạng thái --}}
                            <div class="form-group full-width">
                                <label for="editRoomStatus">
                                    <i class="fa-solid fa-gauge-high"></i>
                                    Trạng thái
                                </label>

                                <select name="status"
                                        id="editRoomStatus"
                                        class="form-select">

                                    <option value="available">Phòng trống</option>
                                    <option value="occupied">Đang thuê</option>
                                    <option value="maintenance">Bảo trì</option>
                                    <option value="inactive">Dừng hoạt động</option>
                                    

                                </select>

                                <small class="field-error"
                                       data-error="status"></small>
                            </div>

                            {{-- Mô tả --}}
                            <div class="form-group full-width">
                                <label for="editRoomDescription">
                                    <i class="fa-solid fa-file-lines"></i>
                                    Mô tả
                                </label>

                                <textarea name="description"
                                          id="editRoomDescription"
                                          class="form-control"
                                          rows="3"
                                          maxlength="1000"
                                          placeholder="Nhập mô tả về phòng..."></textarea>

                                <small class="field-error"
                                       data-error="description"></small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="room-modal-footer">

                    <button type="button"
                            class="btn-cancel-room"
                            data-edit-room-close>
                        <i class="fa-solid fa-xmark"></i>
                        Hủy
                    </button>

                    <button type="submit"
                            class="btn-submit-room"
                            id="btnUpdateRoom">

                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu thay đổi</span>

                    </button>

                </div>

            </form>
        </div>
    </div>
</div>
