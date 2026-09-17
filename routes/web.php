<?php

use Illuminate\Support\Facades\Route;

// =========================================================
// AUTH
// =========================================================
use App\Http\Controllers\Auth\LoginController;

// =========================================================
// STAFF
// =========================================================
use App\Http\Controllers\Staff\ServiceController;
use App\Http\Controllers\Staff\CustomerController;
use App\Http\Controllers\Staff\OrderController;
use App\Http\Controllers\Staff\LoyaltyProgramController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\CustomerMapsController;

// =========================================================
// OWNER
// =========================================================
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

// =========================================================
// CUSTOMER
// =========================================================
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CustomerPromoController;


// =========================================================
// HALAMAN AWAL
// =========================================================

Route::get('/', function () {
    return redirect()->route('customer.home');
});


// =========================================================
// CUSTOMER - PUBLIC
// =========================================================

// Halaman utama customer
Route::get('/customer', [CustomerHomeController::class, 'index'])
    ->name('customer.home');

// Halaman promo
Route::get('/promo', [CustomerPromoController::class, 'index'])
    ->name('customer.promo');


// =========================================================
// AUTHENTICATION
// =========================================================

// Login hanya untuk user yang belum login
Route::middleware('guest')->group(function () {

    // Menampilkan halaman login
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    // Memproses login
    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});


// =========================================================
// USER YANG SUDAH LOGIN
// =========================================================

Route::middleware('auth')->group(function () {

    // =====================================================
    // LOGOUT
    // =====================================================

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');


    // =====================================================
    // DASHBOARD
    // =====================================================

    // Dashboard Staff
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    // Dashboard Owner
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');


    // =====================================================
    // STAFF - LAYANAN
    // =====================================================

    Route::prefix('staff')->name('staff.')->group(function () {

        // Daftar layanan
        Route::get('/services', [ServiceController::class, 'index'])
            ->name('services.index');

        // Tambah layanan
        Route::post('/services', [ServiceController::class, 'store'])
            ->name('services.store');

        // Update layanan
        Route::put('/services/{service}', [ServiceController::class, 'update'])
            ->name('services.update');

        // Hapus layanan
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])
            ->name('services.destroy');

        // Nonaktifkan layanan
        Route::put('/services/{service}/deactivate', [ServiceController::class, 'deactivate'])
            ->name('services.deactivate');

        // Aktifkan kembali layanan
        Route::put('/services/{service}/reactivate', [ServiceController::class, 'reactivate'])
            ->name('services.reactivate');


        // =================================================
        // STAFF - PAKET LAYANAN
        // =================================================

        // Update paket layanan
        Route::put(
            '/service-packages/{servicePackage}',
            [ServiceController::class, 'updatePackage']
        )->name('service-packages.update');

        // Hapus paket layanan
        Route::delete(
            '/service-packages/{servicePackage}',
            [ServiceController::class, 'destroyPackage']
        )->name('service-packages.destroy');

        // Nonaktifkan paket layanan
        Route::put(
            '/service-packages/{servicePackage}/deactivate',
            [ServiceController::class, 'deactivatePackage']
        )->name('service-packages.deactivate');


        // =================================================
        // STAFF - CUSTOMER
        // =================================================

        // Daftar customer
        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        // Tambah customer
        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');

        // Form edit customer
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('customers.edit');

        // Update customer
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');

        // Resolve Google Maps
        Route::post(
            '/customers/resolve-map',
            [CustomerMapsController::class, 'resolve']
        )->name('customers.resolve-map');


        // =================================================
        // STAFF - ORDER
        // =================================================

        // Daftar order
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        // Tambah order
        Route::post('/orders', [OrderController::class, 'store'])
            ->name('orders.store');

        // Riwayat order
        Route::get('/order-history', [OrderController::class, 'history'])
            ->name('orders.history');

        // Tandai pembayaran lunas
        Route::put(
            '/orders/{order}/mark-paid',
            [OrderController::class, 'markAsPaid']
        )->name('orders.mark-paid');

        // Update status order
        Route::put(
            '/orders/{order}/status',
            [OrderController::class, 'updateStatus']
        )->name('orders.updateStatus');


        // =================================================
        // STAFF - LOYALTY
        // =================================================

        // Daftar program loyalty
        Route::get('/loyalty', [LoyaltyProgramController::class, 'index'])
            ->name('loyalty.index');

        // Arsip loyalty
        Route::get('/loyalty/archive', [LoyaltyProgramController::class, 'archive'])
            ->name('loyalty.archive');

        // Tambah program loyalty
        Route::post('/loyalty', [LoyaltyProgramController::class, 'store'])
            ->name('loyalty.store');

        // Form edit loyalty
        Route::get(
            '/loyalty/{loyaltyProgram}/edit',
            [LoyaltyProgramController::class, 'edit']
        )->name('loyalty.edit');

        // Update loyalty
        Route::put(
            '/loyalty/{loyaltyProgram}',
            [LoyaltyProgramController::class, 'update']
        )->name('loyalty.update');

        // Detail loyalty
        Route::get(
            '/loyalty/{loyaltyProgram}',
            [LoyaltyProgramController::class, 'show']
        )->name('loyalty.show');
    });


    // =====================================================
    // OWNER - KELOLA STAFF
    // =====================================================

    Route::prefix('owner')->name('owner.')->group(function () {

        // Daftar staff
        Route::get('/staff', [StaffController::class, 'index'])
            ->name('staff.index');

        // Tambah staff
        Route::post('/staff', [StaffController::class, 'store'])
            ->name('staff.store');

        // Aktif / nonaktif staff
        Route::patch(
            '/staff/{staff}/toggle-status',
            [StaffController::class, 'toggleStatus']
        )->name('staff.toggleStatus');
    });


    // =====================================================
    // CUSTOMER - AKUN
    // =====================================================

    // Halaman akun
    Route::get('/akun', [CustomerAccountController::class, 'index'])
        ->name('customer.account');

    // Update akun
    Route::put('/akun', [CustomerAccountController::class, 'update'])
        ->name('customer.account.update');


    // =====================================================
    // CUSTOMER - PESANAN
    // =====================================================

    // Ambil sendiri
    Route::post(
        '/customer/pesanan/{orderId}/ambil-sendiri',
        [CustomerAccountController::class, 'pickupOrder']
    )->name('customer.order.pickup');

    // Minta diantar
    Route::post(
        '/customer/pesanan/{orderId}/minta-diantar',
        [CustomerAccountController::class, 'deliveryRequest']
    )->name('customer.order.delivery');

    // Riwayat pesanan customer
    Route::get(
        '/pesanan-saya',
        [CustomerAccountController::class, 'orderHistory']
    )->name('customer.orders.history');
});