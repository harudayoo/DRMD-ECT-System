<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OtpController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return Inertia::render('User/Login', [
            'canLogin' => Route::has('user.dashboard'),
        ]);
    })->name('login');

    Route::get('/admin', function () {
        return Inertia::render('Admin/AdminLogin', [
            'canLogin' => Route::has('admin.dashboard'),
            'canRegister' => Route::has('admin.register'),
        ]);
    })->name('admin.login');
});

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/main', [ProvinceController::class, 'index'])->name('user.dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');


    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
    Route::get('/municipalities/{provinceID}', [MunicipalityController::class, 'index'])->name('municipalities.index');
});

// Admin routes
Route::middleware(['web', 'auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');
    Route::get('/check', function () {
        return response()->json(['authenticated' => Auth::guard('admin')->check()]);
    })->name('admin.check');
    Route::get('/update', function () {
        return Inertia::render('Admin/Update');
    })->name('admin.update');
    Route::get('/request', function () {
        return Inertia::render('Admin/Request');
    })->name('admin.request');

    // User management routes
    Route::post('/verify-password', [AdminController::class, 'verifyPassword']);
    Route::get('/users', [AdminController::class, 'ge tUsers'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
});

// Registration route
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('auth:admin')
    ->name('register');


Route::middleware(['auth'])->group(function () {
    Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/send-otp', [OtpController::class, 'send'])->name('otp.send');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
});


// Include authentication routes
require __DIR__ . '/auth.php';
