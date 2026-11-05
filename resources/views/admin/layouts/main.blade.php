<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    @include('admin/layouts/partials/header_admin')
    @include('admin/layouts/partials/sidebar_admin')
    @yield('content')
    <script src="/Javascript/admin.js"></script>
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
</body>

</html>