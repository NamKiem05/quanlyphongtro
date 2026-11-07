<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    @include('admin/layouts/partials/header_admin')
    @include('admin/layouts/partials/sidebar_admin')
    <main class="main main-page">
    @yield('content')
    </main>
    @include('admin/layouts/partials/chatbot')
    @include('admin/layouts/partials/toast_admin')
    
    @vite(['resources/js/chatbot/chatbot.js'])
</body>

</html>