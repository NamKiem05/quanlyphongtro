<?php

use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ContractController;

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
    Route::delete('/tenants/delete/{tenant}', [TenantController::class, 'delete'])
        ->name('tenants.delete');
    // xuất excel
    Route::get('/tenants/export', [TenantController::class, 'export'])
        ->name('tenants.export');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])
        ->middleware('auth')
        ->name('chatbot.send');

    // quản lý phòng
    Route::get('/rooms', [RoomController::class, 'index'])
        ->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])
        ->name('rooms.store');
    Route::get('/rooms/{room}/detail', [RoomController::class, 'detail'])
        ->name('rooms.detail');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])
        ->name('rooms.update');
    Route::delete('/rooms/destroy/{room}', [RoomController::class, 'delete'])
        ->name('rooms.destroy');

    //Quản lý hợp đồng
    Route::get('/contracts/create-data', [ContractController::class, 'createData'])
        ->name('contracts.create-data');
    Route::post('/contracts', [ContractController::class, 'store'])
        ->name('contracts.store');
    Route::get('/contracts', [ContractController::class, 'index'])
        ->name('contracts.index');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])
        ->name('contracts.show');
    Route::get('/contracts/{contract}/edit-data', [ContractController::class, 'editData'])
        ->name('contracts.edit-data');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])
        ->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])
        ->name('contracts.destroy');
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
