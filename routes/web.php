<?php

use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/auth/login');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    //Quản lý nhà trọ
    Route::get('/tenants', [TenantController::class, 'index'])
        ->name('tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])
        ->name('tenants.store');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])
        ->name('tenants.update');
});

// Khu vực Client
Route::middleware('auth')->prefix('client')->name('client.')->group(function () {
    Route::get('/home', function () {
        return view('client.home');
    })->name('home');
});


//Login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
