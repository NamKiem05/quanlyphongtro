<!-- form modal -->
<div class="modal-overlay" id="tenantModal">
    <div class="box-modal">
        <div class="modal-header">
            <div>
                <h3 id="modalTitle">Thêm người thuê</h3>
                <p>Nhập thông tin người thuê mới</p>
            </div>
        </div>

        <form id="tenantForm"
            action="{{ route('tenants.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-group">
                <label>Họ và tên <span>*</span></label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" placeholder="Nhập họ và tên" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Số điện thoại <span>*</span></label>
                    <input type="text" name="phone" id="phone" placeholder="Nhập số điện thoại">
                </div>

                <div class="form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Số CCCD <span>*</span></label>
                    <input type="text" name="identity_number" id="identity_number" placeholder="Nhập số CCCD">
                </div>

                <div class="form-group">
                    <label>Ngày sinh <span>*</span></label>
                    <input type="date" name="date_of_birth" id="date_of_birth">
                </div>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <select name="gender" id="gender">
                    <option value="">Chọn giới tính <span>*</span></option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ảnh căn cước công dân <span>*</span></label>

                <div class="cccd-upload">
                    <div class="upload-item">
                        <label for="identity_front" class="upload-box">
                            <i class="fa-solid fa-plus"></i>
                            <img id="previewFront" src="" alt="">
                        </label>
                        <input type="file" name="identity_front"
                            id="identity_front" accept="image/*" hidden>
                        <span>Mặt trước</span>
                    </div>

                    <div class="upload-item">
                        <label for="identity_back" class="upload-box">
                            <i class="fa-solid fa-plus"></i>
                            <img id="previewBack" src="" alt="">
                        </label>
                        <input type="file" name="identity_back"
                            id="identity_back" accept="image/*" hidden>
                        <span>Mặt sau</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Địa chỉ thường trú <span>*</span></label>
                <input type="text" name="permanent_address"
                    id="permanent_address" placeholder="Xã, phường, tỉnh,.....">
            </div>

            <div class="form-group">
                <label>Ghi chú <span>*</span></label>
                <textarea name="note" id="note" placeholder="Nhập ghi chú"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" id="cancelModal" class="btn-close-modal">
                    Hủy
                </button>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="fa-solid fa-download"></i>
                    Lưu
                </button>
            </div>

        </form>
    </div>

</div>