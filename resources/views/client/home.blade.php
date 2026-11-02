<h1>Trang chủ khách thuê</h1>

<p>Xin chào {{ auth()->user()->name }}</p>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Đăng xuất</button>
</form>