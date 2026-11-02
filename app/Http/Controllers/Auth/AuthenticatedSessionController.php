<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Kiểm tra thông tin đăng nhập
        $request->authenticate();

        // Tạo lại Session
        $request->session()->regenerate();

        // Lấy tài khoản đang đăng nhập
        $user = auth()->user();

        // Phân quyền chuyển hướng
        if ($user->role === 'owner') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'user') {
            return redirect()->route('client.home');
        }

        // Nếu tài khoản chưa được phân quyền
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Tài khoản chưa được phân quyền!',
            ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
