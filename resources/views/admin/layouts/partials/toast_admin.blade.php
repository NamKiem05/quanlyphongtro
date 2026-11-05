 <!-- {{-- Toast thông báo thành công --}} -->
    @if (session('success'))
    <div class="toast-message success" id="toastMessage">
        <div class="toast-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="toast-content">
            <strong>Thành công</strong>
            <p>{{ session('success') }}</p>
        </div>

        <button type="button" class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- {{-- Toast thông báo thất bại --}} -->
    @if (session('error'))
    <div class="toast-message error" id="toastMessage">
        <div class="toast-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>

        <div class="toast-content">
            <strong>Thất bại</strong>
            <p>{{ session('error') }}</p>
        </div>

        <button type="button" class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif