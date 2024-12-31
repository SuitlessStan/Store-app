<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductSupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


// Home Route
Route::get('/', function () {
    return view('l5-swagger::index');
});

// Dashboard Route (Requires Authentication)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes (Requires Authentication and Admin Middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('/admin/customers', CustomerController::class);
    Route::resource('/admin/orders', OrderController::class);
    Route::resource('/admin/products', ProductController::class);
    Route::resource('/admin/suppliers', SupplierController::class);
    Route::resource('/admin/users', UserController::class);

    Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');

    Route::get('/admin/suppliers/create', [AdminController::class, 'createSupplier'])->name('admin.suppliers.create');
    Route::post('/admin/suppliers', [AdminController::class, 'storeSupplier'])->name('admin.suppliers.store');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CSRF Token Route for SPA Authentication
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF token initialized']);
});

// // Swagger Documentation
Route::get('/api/documentation', function () {
    return view('l5-swagger::index');
});

// Include Authentication Routes
require __DIR__ . '/auth.php';