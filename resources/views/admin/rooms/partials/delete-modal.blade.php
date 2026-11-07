<style>
 
</style>
<div class="delete-modal" id="deleteModal">
    <div class="delete-modal-overlay"></div>

    <div class="delete-modal-content">

        <div class="delete-modal-header">
            <h5>Xác nhận xóa phòng</h5>

            <button type="button" id="btnCloseDeleteModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="delete-modal-body">
            <div class="delete-icon">
                <i class="fa-solid fa-trash"></i>
            </div>

            <p>
                Anh có chắc chắn muốn xóa phòng
                <strong id="deleteItemName"></strong>?
            </p>

            <small>
                Lưu ý: Chỉ có thể xóa phòng chưa từng có hợp đồng.
            </small>
        </div>

        <div class="delete-modal-footer">

            <button type="button" id="btnCancel">
                Hủy
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit">
                    <i class="fa-solid fa-trash"></i>
                    Xác nhận xóa
                </button>
            </form>

        </div>
    </div>
</div>