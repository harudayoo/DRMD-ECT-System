<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterlistController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\RCDController;
use App\Http\Controllers\CDRController;
use App\Http\Controllers\DvPayrollController;
use App\Http\Controllers\OrsBursController;
use App\Http\Controllers\RespCodeController;
use App\Http\Controllers\UacsCodeController;
use App\Http\Controllers\PaymentNatureController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SearchController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth:web,admin'])->group(function () {
    Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/send-otp', [OtpController::class, 'send'])->name('otp.send');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
});

// User routes
Route::middleware(['auth:web,admin', 'verified'])->group(function () {
    Route::get('/main', [ProvinceController::class, 'index'])->name('user.dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
    Route::get('/municipalities/{provinceID}', [MunicipalityController::class, 'index'])->name('municipalities.index');
    Route::get('/barangays/{municipalityID}', [BarangayController::class, 'index'])->name('barangays.index');
    Route::get('/barangay/{barangayID}/{barangayName?}', [BarangayController::class, 'masterlist'])
        ->name('barangay.masterlist');


    // Beneficiary Routes
    Route::get('/add-beneficiary', [BeneficiaryController::class, 'create'])->name('beneficiaries.create');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
    Route::post('/beneficiaries/confirm', [BeneficiaryController::class, 'confirmAdd'])->name('beneficiaries.confirmAdd');
    Route::get('/api/provinces', [BeneficiaryController::class, 'getProvinces'])->name('api.provinces.index');
    Route::get('/api/municipalities', [BeneficiaryController::class, 'getMunicipalities'])->name('api.municipalities.index');
    Route::get('/api/barangays', [BeneficiaryController::class, 'getBarangays'])->name('api.barangays.index');
    Route::get('/api/masterlists', [BeneficiaryController::class, 'getMasterlists'])->name('api.masterlists.index');
    Route::put('/api/beneficiaries/{beneficiaryID}', [BeneficiaryController::class, 'update'])->name('beneficiaries.update');
    Route::get('/edit-beneficiary', [BeneficiaryController::class, 'index'])->name('beneficiaries.edit');

    //Adding Beneficiary to Masterlist
    Route::get('/api/beneficiaries', [MasterlistController::class, 'getExistingBeneficiaries'])->name('masterlists.existing-beneficiaries');
    Route::post('/api/beneficiaries/add-to-masterlist', [MasterlistController::class, 'addToMasterlist'])->name('masterlists.add-beneficiary');

    // Masterlist Routes
    Route::get('/view-masterlists', [MasterlistController::class, 'index'])->name('masterlists.view');
    Route::get('/api/masterlists', [MasterlistController::class, 'getMasterlists'])->name('api.masterlists.index');
    Route::post('/api/masterlists/store', [MasterlistController::class, 'store'])->name('masterlists.store');
    Route::put('/api/masterlists/{masterlistID}', [MasterlistController::class, 'update'])->name('masterlists.update');
    Route::post('/api/masterlists/import', [MasterlistController::class, 'import'])->name('masterlists.import');
    Route::post('/masterlist/preview', [MasterlistController::class, 'preview'])->name('masterlist.preview');
    Route::get('/api/masterlists/{masterlistID}/beneficiaries', [MasterlistController::class, 'getBeneficiaries'])->name('api.masterlists.beneficiaries');
    Route::get('/api/masterlists/{masterlistID}/export', [MasterlistController::class, 'export'])->name('api.masterlists.export');

    // Payroll Routes
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollId}/beneficiaries', [PayrollController::class, 'getBeneficiaries'])->name('payroll.beneficiaries');
    Route::post('/payroll/{payrollId}/beneficiaries', [PayrollController::class, 'updateBeneficiaries'])->name('payroll.updateBeneficiaries');
    Route::get('/payroll/{payrollId}/export', [PayrollController::class, 'export'])->name('payroll.export');
    Route::post('/payroll/{payrollId}/mark-all-claimed', [PayrollController::class, 'markAllClaimed'])->name('payroll.markAllClaimed');

    // API Payroll Routes
    Route::prefix('api')->group(function () {
        Route::get('/provinces', [PayrollController::class, 'getProvinces'])->name('api.provinces.index');
        Route::get('/municipalities', [PayrollController::class, 'getMunicipalities'])->name('api.municipalities.index');
        Route::get('/barangays', [PayrollController::class, 'getBarangays'])->name('api.barangays.index');
    });

    //Document Routes`

    //RCD Routes
    Route::get('/rcd', [RCDController::class, 'index'])->name('rcd.index');
    Route::post('/rcds', [RcdController::class, 'store'])->name('rcd.store');
    Route::get('/rcd/beneficiaries', [RCDController::class, 'getBeneficiaries'])->name('rcd.beneficiaries');
    Route::get('/rcds/export/{rcdID}', [RCDController::class, 'export'])->name('rcds.export');
    Route::post('/rcds/{rcdID}/fund-center', [RCDController::class, 'storeFundCenter'])->name('rcds.fund-center.store');

    // API routes group
    Route::prefix('api')->group(function () {
        Route::get('/dvPayroll', [DvPayrollController::class, 'index'])->name('api.dvPayroll.index');
        Route::post('/dvPayroll', [DvPayrollController::class, 'store'])->name('api.dvPayroll.store');

        Route::get('/orsBurs', [OrsBursController::class, 'index'])->name('api.orsBurs.index');
        Route::post('/orsBurs', [OrsBursController::class, 'store'])->name('api.orsBurs.store');

        Route::get('/respCode', [RespCodeController::class, 'index'])->name('api.respCode.index');
        Route::post('/respCode', [RespCodeController::class, 'store'])->name('api.respCode.store');

        Route::get('/uacsCode', [UacsCodeController::class, 'index'])->name('api.uacsCode.index');
        Route::post('/uacsCode', [UacsCodeController::class, 'store'])->name('api.uacsCode.store');

        Route::get('/paymentNature', [PaymentNatureController::class, 'index'])->name('api.paymentNature.index');
        Route::post('/paymentNature', [PaymentNatureController::class, 'store'])->name('api.paymentNature.store');

        Route::patch('/rcd/{rcdID}', [RCDController::class, 'update'])->name('api.rcd.update');
    });

    //CDR Routes
    Route::prefix('cdr')->group(function () {
        Route::get('/', [CDRController::class, 'index'])->name('cdr.index');
        Route::post('/', [CDRController::class, 'store'])->name('cdr.store');
        Route::get('/beneficiaries', [CDRController::class, 'getBeneficiaries'])->name('cdr.beneficiaries');
        Route::get('/search-rcds', [CDRController::class, 'searchRcds'])->name('cdr.searchRcds');
        Route::get('/{rcdID}', [RCDController::class, 'show'])->name('rcd.show');
    });

    //Analytics Routes
    Route::get('/status-analytics/{municipalityId}', [AnalyticsController::class, 'getStatusAnalytics']);

    //Search Route
    Route::get(uri: '/search', action: [SearchController::class, 'search'])->name(name: 'search.bar');
});

// Admin routes
Route::middleware(['auth:admin', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index',])->name('admin.dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboardChart',])->name('admin.dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');


    Route::get('/update', [AdminController::class, 'showUpdatePage'])->name('admin.update');
    Route::get('/request', function () {
        return Inertia::render('Admin/Request');
    })->name('admin.request');

    // User management route
    Route::get('/users', [AdminController::class, 'getUsers'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/update', [AdminController::class, 'showUpdatePage'])->name('admin.update');
});

// Registration route
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('auth:admin')
    ->name('register');

//Register Request Route
Route::post('/register-request', [RegisteredUserController::class, 'storeRequest'])
    ->middleware('auth:admin')
    ->name('register');

// Include authentication routes
require __DIR__ . '/auth.php';

