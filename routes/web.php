<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;

// Authentication routes (no tenant required)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login-modern', [AuthController::class, 'showModernLoginForm'])->name('login.modern');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/login-modern', [AuthController::class, 'loginModern'])->name('login.modern.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tenant selection (for when user is logged in but no tenant selected)
Route::get('/tenant/select', [DashboardController::class, 'selectTenant'])->name('tenant.select');
Route::post('/tenant/set', [DashboardController::class, 'setTenant'])->name('tenant.set');

// Protected routes (require auth and tenant)
Route::middleware(['auth', 'tenant'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Sales
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');

    // Deliveries
    Route::resource('deliveries', DeliveryController::class);
    Route::patch('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/stock', [ProductController::class, 'stock'])->name('products.stock');
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});

// Fallback route
Route::get('/welcome', function () {
    return view('welcome');
});
