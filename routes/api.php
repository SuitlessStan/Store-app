<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSupplierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
/**
 * @OA\Info(
 *     title="Electronic Store API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the Electronic Store application",
 * )
 */



//Route::middleware('api')->group(function () {
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/password/email', [PasswordResetLinkController::class, 'store']);
Route::post('/password/reset', [NewPasswordController::class, 'store']);

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/resend', [EmailVerificationNotificationController::class, 'store'])->middleware('auth:sanctum');
//});

Route::middleware(['auth:sanctum'])->group(function () {
    // Customers
    /**
     * @OA\Tag(
     *     name="Customers",
     *     description="API Endpoints for Customers"
     * )
     */
    Route::apiResource('customers', CustomerController::class);

    // Orders
    /**
     * @OA\Tag(
     *     name="Orders",
     *     description="API Endpoints for Orders"
     * )
     */
    Route::apiResource('orders', OrderController::class);

    // Order Details
    /**
     * @OA\Tag(
     *     name="Order Details",
     *     description="API Endpoints for Order Details"
     * )
     */
    Route::apiResource('order.order-details', OrderDetailController::class);

    // Products
    /**
     * @OA\Tag(
     *     name="Products",
     *     description="API Endpoints for Products"
     * )
     */
    Route::apiResource('products', ProductController::class);

    // Product Suppliers
    /**
     * @OA\Tag(
     *     name="Product Suppliers",
     *     description="API Endpoints for Product Suppliers"
     * )
     */
    Route::apiResource('product-suppliers', ProductSupplierController::class);

    // Profiles
    /**
     * @OA\Tag(
     *     name="Profiles",
     *     description="API Endpoints for Profiles"
     * )
     */
    Route::apiResource('profiles', ProfileController::class);

    // Suppliers
    /**
     * @OA\Tag(
     *     name="Suppliers",
     *     description="API Endpoints for Suppliers"
     * )
     */
    Route::apiResource('suppliers', SupplierController::class);

    // Users
    /**
     * @OA\Tag(
     *     name="Users",
     *     description="API Endpoints for Users"
     * )
     */
    Route::apiResource('users', UserController::class);

    // Admins
    /**
     * @OA\Tag(
     *     name="Admins",
     *     description="API Endpoints for Admins"
     * )
     */
    Route::apiResource('admins', AdminController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('favorites', FavoritesController::class)->only(['index', 'store', 'destroy']);
        Route::apiResource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
    });

});
