<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->middleware('throttle:api')->group(function () {

    // ── PUBLIC ──────────────────────────────────────────────────
    Route::get('/menus',           [ApiController::class, 'menus'])->name('menus');
    Route::get('/menus/{menu}',    [ApiController::class, 'menuDetail'])->name('menus.show');
    Route::get('/categories',      [ApiController::class, 'categories'])->name('categories');
    Route::get('/track',           [ApiController::class, 'track'])->name('track');
    Route::get('/reviews',         [ApiController::class, 'reviews'])->name('reviews');
    Route::get('/price-config',    [ApiController::class, 'priceConfig'])->name('price-config');
    Route::get('/stats',           [ApiController::class, 'stats'])->name('stats');
    Route::get('/settings/qris',   [ApiController::class, 'qrisPublic'])->name('settings.qris');
    Route::post('/coupons/check',  [ApiController::class, 'checkCoupon'])->name('coupons.check');

    // Auth — hanya login (register dihapus, akun dibuat via tinker/seeder)
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Buat pesanan (guest)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Payment guest
    Route::get('/orders/{order}/payment',         [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/orders/{order}/payment/upload', [PaymentController::class, 'upload'])->name('payment.upload');

    // ── PROTECTED ───────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout',       [AuthController::class, 'logout'])->name('logout');
        Route::get('/user',          [AuthController::class, 'user'])->name('user');
        Route::put('/user/profile',  [AuthController::class, 'updateProfile'])->name('user.profile');
        Route::put('/user/password', [AuthController::class, 'updatePassword'])->name('user.password');

        // Orders customer (login)
        Route::get('/orders',                           [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',                   [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel',           [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])->name('orders.confirm-received');

        // Review
        Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('review.store');

        // ── ADMIN ──────────────────────────────────────────────
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::apiResource('/menus',      AdminMenuController::class);
            Route::apiResource('/categories', AdminCategoryController::class);

            // Orders
            Route::get('/orders',                             [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}',                     [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('/orders/{order}/status',            [AdminOrderController::class, 'updateStatus'])->name('orders.status');
            Route::post('/custom-menus/{customMenu}/approve', [AdminOrderController::class, 'approveCustomMenu'])->name('custom-menus.approve');
            Route::post('/custom-menus/{customMenu}/reject',  [AdminOrderController::class, 'rejectCustomMenu'])->name('custom-menus.reject');

            // Payments
            Route::get('/payments',                   [AdminPaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
            Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

            // Customers
            Route::get('/customers',           [\App\Http\Controllers\Api\Admin\CustomerController::class, 'index'])->name('customers.index');
            Route::get('/customers/{user}',    [\App\Http\Controllers\Api\Admin\CustomerController::class, 'show'])->name('customers.show');
            Route::delete('/customers/{user}', [\App\Http\Controllers\Api\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');

            // Coupons
            Route::get('/coupons',                   [\App\Http\Controllers\Api\Admin\CouponController::class, 'index'])->name('coupons.index');
            Route::post('/coupons',                  [\App\Http\Controllers\Api\Admin\CouponController::class, 'store'])->name('coupons.store');
            Route::put('/coupons/{coupon}',          [\App\Http\Controllers\Api\Admin\CouponController::class, 'update'])->name('coupons.update');
            Route::delete('/coupons/{coupon}',       [\App\Http\Controllers\Api\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
            Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Api\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');

            // Reviews
            Route::get('/reviews',             [\App\Http\Controllers\Api\Admin\ReviewController::class, 'index'])->name('reviews.index');
            Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

            // Activity Logs
            Route::get('/activity-logs', [\App\Http\Controllers\Api\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

            // Settings
            Route::get('/settings',          [\App\Http\Controllers\Api\Admin\SettingsController::class, 'index'])->name('settings.index');
            Route::patch('/settings',        [\App\Http\Controllers\Api\Admin\SettingsController::class, 'update'])->name('settings.update');
            Route::patch('/settings/app',    [\App\Http\Controllers\Api\Admin\SettingsController::class, 'updateApp'])->name('settings.app');
            Route::post('/settings/qris',    [\App\Http\Controllers\Api\Admin\SettingsController::class, 'uploadQris'])->name('settings.qris.upload');
            Route::delete('/settings/qris',  [\App\Http\Controllers\Api\Admin\SettingsController::class, 'deleteQris'])->name('settings.qris.delete');
        });
    });
});
