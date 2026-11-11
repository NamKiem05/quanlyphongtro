<div class="room-modal" id="createRoomModal">

    <div class="room-modal-overlay" data-create-room-close></div>

    <div class="room-modal-dialog create-room-dialog">
        <div class="room-modal-content">

            {{-- Header --}}
            <div class="room-modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-house-circle-plus"></i>
                    Thêm phòng mới
                </h5>

                <button type="button"
                    class="btn-close-room-icon"
                    data-create-room-close
                    aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="createRoomForm"
                class="create-room-form"
                data-url="{{ route('rooms.store') }}"
                novalidate>
                @csrf

                <div class="room-modal-body">

                    <div id="createRoomError" class="alert alert-danger d-none"></div>

                    <div class="detail-section create-room-section">
                        <div class="detail-section-header">
                            <i class="fa-solid fa-building"></i>
                            <h6>Thông tin phòng</h6>
                        </div>

                        <div class="create-room-form-grid">

                            <div class="form-group">
                                <label for="roomName">
                                    <i class="fa-solid fa-bed"></i>
                                    Tên phòng <span class="required">*</span>
                                </label>
                                <input type="text"
                                    name="name"
                                    id="roomName"
                                    class="form-control"
                                    placeholder="Ví dụ: P101"
                                    autocomplete="off">
                                <small class="field-error" data-error="name"></small>
                            </div>

                            <div class="form-group">
                                <label for="roomFloor">
                                    <i class="fa-solid fa-stairs"></i>
                                    Tầng
                                </label>
                                <input type="number"
                                    name="floor"
                                    id="roomFloor"
                                    class="form-control"
                                    min="1"
                                    placeholder="Ví dụ: 1">
                                <small class="field-error" data-error="floor"></small>
                            </div>

                            <div class="form-group">
                                <label for="roomArea">
                                    <i class="fa-solid fa-expand"></i>
                                    Diện tích
                                </label>
                                <div class="input-with-unit">
                                    <input type="number"
                                        name="area"
                                        id="roomArea"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        placeholder="Ví dụ: 25">
                                    <span class="input-unit">m²</span>
                                </div>
                                <small class="field-error" data-error="area"></small>
                            </div>

                            <div class="form-group">
                                <label for="roomPrice">
                                    <i class="fa-solid fa-sack-dollar"></i>
                                    Giá thuê <span class="required">*</span>
                                </label>
                                <div class="input-with-unit">
                                    <input type="number"
                                        name="rent_price"
                                        id="roomPrice"
                                        class="form-control"
                                        min="0"
                                        step="1000"
                                        placeholder="Ví dụ: 3000000">
                                    <span class="input-unit">VNĐ</span>
                                </div>
                                <small class="field-error" data-error="rent_price"></small>
                            </div>

                            <div class="form-group full-width">
                                <label for="roomStatus">
                                    <i class="fa-solid fa-gauge-high"></i>
                                    Trạng thái
                                </label>
                                <select name="status" id="roomStatus" class="form-select">
                                    <option value="available">Phòng trống</option>
                                    <option value="occupied">Đang thuê</option>
                                    <option value="maintenance">Bảo trì</option>
                                </select>
                                <small class="field-error" data-error="status"></small>
                            </div>

                            <div class="form-group full-width">
                                <label for="roomDescription">
                                    <i class="fa-solid fa-file-lines"></i>
                                    Mô tả
                                </label>
                                <textarea name="description"
                                    id="roomDescription"
                                    class="form-control"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="Nhập mô tả về phòng..."></textarea>
                                <small class="field-error" data-error="description"></small>
                            </div>

                        </div>
                    </div>
                    {{-- Dịch vụ áp dụng cho phòng --}}
                    <div class="detail-section create-room-section">
                        <div class="detail-section-header">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                                Các loại dịch vụ
                            </h5>
                        </div>

                        <div class="room-services-list">
                            @forelse ($services as $service)
                            <label class="room-service-item">
                                <input
                                    type="checkbox"
                                    name="services[]"
                                    value="{{ $service->id }}">

                                <div class="room-service-info">
                                    <span class="room-service-name">
                                        {{ $service->name }}
                                    </span>

                                    <small>
                                        {{ $service->type === 'metered' ? 'Dịch vụ tính theo chỉ số' : 'Dịch vụ cố định' }}
                                        @if ($service->unit)
                                        · Đơn vị: {{ $service->unit }}
                                        @endif
                                    </small>
                                </div>

                                <div class="room-service-price">
                                    <input
                                        type="number"
                                        name="service_prices[{{ $service->id }}]"
                                        value="{{ $service->unit_price }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="Đơn giá">
                                </div>
                            </label>
                            @empty
                            <p class="text-muted">
                                Chưa có dịch vụ nào. Anh hãy tạo dịch vụ trước.
                            </p>
                            @endforelse
                        </div>
                    </div>
                </div>


                {{-- Footer --}}
                <div class="room-modal-footer">
                    <button type="button" class="btn-cancel-room" data-create-room-close>
                        <i class="fa-solid fa-xmark"></i>
                        Hủy
                    </button>

                    <button type="submit" class="btn-submit-room" id="btnSubmitRoom">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm phòng</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>