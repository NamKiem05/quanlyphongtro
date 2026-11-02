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
    @yield('main')
    <script src="/Javascript/admin.js"></script>
</body>

</html>