<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return Inertia::render('User/Login', [
            'canLogin' => Route::has('user.dashboard'),
        ]);
    })->name('login'); // Changed from 'user.login' to 'login' for consistency

    Route::get('/admin', function () {
        return Inertia::render('Admin/AdminLogin', [
            'canLogin' => Route::has('admin.dashboard'),
            'canRegister' => Route::has('admin.register'),
        ]);
    })->name('admin.login');
});

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/main', function () {
        return Inertia::render('User/MainDashboard');
    })->name('user.dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');



    // Davao Region routes
    Route::prefix('User')->group(function () {
        Route::get('/davao-de-oro', function () {
            return Inertia::render('User/DeOro');
        })->name('oro.dashboard');

        Route::get('/davao-oriental', function () {
            return Inertia::render('User/Oriental');
        })->name('oriental.dashboard');

        Route::get('/davao-occidental', function () {
            return Inertia::render('User/Occidental');
        })->name('occidental.dashboard');

        Route::get('/davao-del-sur', function () {
            return Inertia::render('User/DelSur');
        })->name('sur.dashboard');

        Route::get('/davao-del-norte', function () {
            return Inertia::render('User/DelNorte');
        })->name('norte.dashboard');

    });


    // Davao de Oro Barangay routes

    Route::get('/municipality/compostela', function () {
        return Inertia::render('OroMncplty/OroM1');
    })->name('Compostela, Davao De Oro');
    Route::get('/municipality/laak', function () {
        return Inertia::render('OroMncplty/OroM2');
    })->name('Laak, Davao De Oro');
    Route::get('/municipality/mabini', function () {
        return Inertia::render('OroMncplty/OroM3');
    })->name('Mabini, Davao De Oro');
    Route::get('/municipality/maco', function () {
        return Inertia::render('OroMncpltyOroM4');
    })->name('Maco, Davao De Oro');
    Route::get('/municipality/maragusan', function () {
        return Inertia::render('OroMncplty/OroM5');
    })->name('Maragusan, Davao De Oro');
    Route::get('/municipality/mawab', function () {
        return Inertia::render('OroMncplty/OroM6');
    })->name('Mawab, Davao De Oro');
    Route::get('/municipality/monkayo', function () {
        return Inertia::render('OroMncplty/OroM7');
    })->name('Monkayo, Davao De Oro');
    Route::get('/municipality/montevista', function () {
        return Inertia::render('OroMncpltyOroM8');
    })->name('Montevista, Davao De Oro');
    Route::get('/municipality/nabunturan', function () {
        return Inertia::render('OroMncplty/OroM9');
    })->name('Nabunturan, Davao De Oro');
    Route::get('/municipality/new-bataan', function () {
        return Inertia::render('OroMncplty/OroM10');
    })->name('New Bataan, Davao De Oro');
    Route::get('/municipality/pantukan', function () {
        return Inertia::render('OroMncplty/OroM11');
    })->name('Pantukan, Davao De Oro');

    //  Davao Oriental Barangay Routes

    Route::get('/municipality/baganga', function () {
        return Inertia::render('OrientalMncplty/OrienM1');
    })->name('Baganga, Davao Oriental');
    Route::get('/municipality/banaybanay', function () {
        return Inertia::render('OrientalMncplty/OrienM2');
    })->name('Banaybanay, Davao Oriental');
    Route::get('/municipality/boston', function () {
        return Inertia::render('OrientalMncplty/OrienM3');
    })->name('Boston, Davao Oriental');
    Route::get('/municipality/caraga', function () {
        return Inertia::render('OrientalMncplty/OrienM4');
    })->name('Caraga, Davao Oriental');
    Route::get('/municipality/cateel', function () {
        return Inertia::render('OrientalMncplty/OrienM5');
    })->name('Cateel, Davao Oriental');
    Route::get('/municipality/governor-generoso', function () {
        return Inertia::render('OrientalMncplty/OrienM6');
    })->name('Governor Generoso, Davao Oriental');
    Route::get('/municipality/lupon', function () {
        return Inertia::render('OrientalMncplty/OrienM7');
    })->name('Lupon, Davao Oriental');
    Route::get('/municipality/manay', function () {
        return Inertia::render('OrientalMncplty/OrienM8');
    })->name('Manay, Davao Oriental');
    Route::get('/municipality/mati', function () {
        return Inertia::render('OrientalMncplty/OrienM9');
    })->name('Mati, Davao Oriental');
    Route::get('/municipality/san-isidro', function () {
        return Inertia::render('OrientalMncplty/OrienM10');
    })->name('San Isidro, Davao Oriental');
    Route::get('/municipality/tarragona', function () {
        return Inertia::render('OrientalMncplty/OrienM11');
    })->name('Tarragona, Davao Oriental');

    //  Davao Occidental Barangay Routes

    Route::get('/municipality/don-marcelino', function () {
        return Inertia::render('OccidentalMncplty/OccM1');
    })->name('Don Marcelino, Davao Occidental');
    Route::get('/municipality/jose-abad-santos', function () {
        return Inertia::render('OccidentalMncplty/OccM2');
    })->name('Jose Abad Santos, Davao Occidental');
    Route::get('/municipality/malita', function () {
        return Inertia::render('OccidentalMncplty/OccM3');
    })->name('Malita, Davao Occidental');
    Route::get('/municipality/santa-maria', function () {
        return Inertia::render('OccidentalMncplty/OccM4');
    })->name('Sta. Maria, Davao Occidental');
    Route::get('/municipality/sarangani', function () {
        return Inertia::render('OccidentalMncplty/OccM5');
    })->name('Sarangani, Davao Occidental');

    //  Davao del Sur Barangay Routes

    Route::get('/municipality/bansalan', function () {
        return Inertia::render('DelSurMncplty/SurM1');
    })->name('Bansalan, Davao Del Sur');
    Route::get('/municipality/davao', function () {
        return Inertia::render('DelSurMncplty/SurM2');
    })->name('Davao, Davao Del Sur');
    Route::get('/municipality/digos', function () {
        return Inertia::render('DelSurMncplty/SurM3');
    })->name('Digos, Davao Del Sur');
    Route::get('/municipality/hagonoy', function () {
        return Inertia::render('DelSurMncplty/SurM4');
    })->name('Hagonoy, Davao Del Sur');
    Route::get('/municipality/kiblawan', function () {
        return Inertia::render('DelSurMncplty/SurM5');
    })->name('Kiblawan, Davao Del Sur');
    Route::get('/municipality/magsaysay', function () {
        return Inertia::render('DelSurMncplty/SurM6');
    })->name('Magsaysay, Davao Del Sur');
    Route::get('/municipality/malalag', function () {
        return Inertia::render('DelSurMncplty/SurM7');
    })->name('Malalag, Davao Del Sur');
    Route::get('/municipality/matanao', function () {
        return Inertia::render('DelSurMncplty/SurM8');
    })->name('Matanao, Davao Del Sur');
    Route::get('/municipality/padada', function () {
        return Inertia::render('DelSurMncplty/SurM9');
    })->name('Padada, Davao Del Sur');
    Route::get('/municipality/santa-cruz', function () {
        return Inertia::render('DelSurMncplty/SurM10');
    })->name('Sta. Cruz, Davao Del Sur');
    Route::get('/municipality/sulop', function () {
        return Inertia::render('DelSurMncplty/SurM11');
    })->name('Sulop, Davao Del Sur');

    //  Davao del Norte Barangay Routes

    Route::get('/municipality/asuncion', function () {
        return Inertia::render('DelNorteMncplty/NorteM1');
    })->name('Asuncion, Davao Del Norte');
    Route::get('/municipality/braulio-e.-dujali', function () {
        return Inertia::render('DelNorteMncplty/NorteM2');
    })->name('Braulio E. Dujali, Davao Del Norte');
    Route::get('/municipality/carmen', function () {
        return Inertia::render('DelNorteMncplty/NorteM3');
    })->name('Carmen, Davao Del Norte');
    Route::get('/municipality/kapalong', function () {
        return Inertia::render('DelNorteMncplty/NorteM4');
    })->name('Kapalong, Davao Del Norte');
    Route::get('/municipality/new-corella', function () {
        return Inertia::render('DelNorteMncplty/NorteM5');
    })->name('New Corella, Davao Del Norte');
    Route::get('/municipality/panabo', function () {
        return Inertia::render('DelNorteMncplty/NorteM6');
    })->name('Panabo, Davao Del Norte');
    Route::get('/municipality/samal', function () {
        return Inertia::render('DelNorteMncplty/NorteM7');
    })->name('Samal, Davao Del Norte');
    Route::get('/municipality/san-isidro', function () {
        return Inertia::render('DelNorteMncplty/NorteM8');
    })->name('San Isidro, Davao Del Norte');
    Route::get('/municipality/santo-tomas', function () {
        return Inertia::render('DelNorteMncplty/NorteM9');
    })->name('Sto. Tomas, Davao Del Norte');
    Route::get('/municipality/tagum', function () {
        return Inertia::render('DelNorteMncplty/NorteM10');
    })->name('Tagum, Davao Del Norte');
    Route::get('/municipality/talaingod', function () {
        return Inertia::render('DelNorteMncplty/NorteM11');
    })->name('Talaingod, Davao Del Norte');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});

// Admin routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/update', function () {
        return Inertia::render('Admin/Update');
    })->name('admin.update');
    Route::get('/request', function () {
        return Inertia::render('Admin/Request');
    })->name('admin.request');

    // delete later
    Route::get('/logs', function () {
        return Inertia::render('Admin/Logs');
    })->name('admin.logs');


    // User management routes
    Route::post('/verify-password', [AdminController::class, 'verifyPassword']);
    Route::get('/users', [AdminController::class, 'getUsers'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

});

// Registration route
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('auth:admin')
    ->name('register');
// Include authentication routes
require __DIR__ . '/auth.php';

// Sample Masterlist
Route::get('/barangay/aurora', function () {
    return Inertia::render('DeOroMasterlist/OroM1mstrlst/M1B1');
})->name('m1b1');

// OTP
Route::get('/otp', function () {
    return Inertia::render('User/OTP');
})->name('otp');

