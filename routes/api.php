<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\Api\V1\JoinVendorController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\PromoController;
use App\Http\Controllers\Api\V1\RealWeddingController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\VendorAdminController;
use App\Http\Controllers\Api\V1\VendorController;
use App\Http\Controllers\Api\V1\VendorLikeController;
use App\Http\Controllers\Api\V1\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — dikonsumsi oleh aplikasi iOS native (SwiftUI)
|--------------------------------------------------------------------------
| Endpoint publik (read-only) tidak butuh autentikasi.
| Endpoint yang mengubah data akan berada di grup auth:sanctum.
*/

Route::prefix('v1')->group(function () {
    // Publik / read-only
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{package:slug}', [PackageController::class, 'show']);

    Route::get('/vendors', [VendorController::class, 'index']);
    Route::get('/vendors/{vendor:slug}', [VendorController::class, 'show']);

    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{blog:slug}', [BlogController::class, 'show']);

    Route::get('/real-weddings', [RealWeddingController::class, 'index']);
    Route::get('/real-weddings/{realWedding:slug}', [RealWeddingController::class, 'show']);

    // Join Vendor — publik (provinces, cities, categories)
    Route::prefix('join-vendor')->group(function () {
        Route::get('/provinces',  [JoinVendorController::class, 'provinces']);
        Route::get('/cities',     [JoinVendorController::class, 'cities']);
        Route::get('/categories', [JoinVendorController::class, 'categories']);
    });

    // Autentikasi — menghasilkan token Sanctum untuk aplikasi iOS
    Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/apple', [AuthController::class, 'apple']);
        Route::post('/google', [AuthController::class, 'google']);
    });

    // Device token FCM — daftar/hapus device untuk push notification
    Route::post('/device-tokens', [PushNotificationController::class, 'register']);
    Route::delete('/device-tokens', [PushNotificationController::class, 'unregister']);

    // Terproteksi — butuh token Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/me/dashboard', [AuthController::class, 'dashboard']);
        Route::get('/me/reviews', [AuthController::class, 'myReviews']);
        Route::post('/me/profile', [AuthController::class, 'updateProfile']);
        Route::post('/me/avatar', [AuthController::class, 'updateAvatar']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Booking
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/vendors/{vendor:slug}/bookings', [BookingController::class, 'store'])
            ->middleware('throttle:10,1');

        // Ulasan vendor
        Route::post('/vendors/{vendor:slug}/reviews', [ReviewController::class, 'store'])
            ->middleware('throttle:10,1');

        // Like vendor
        Route::post('/vendors/{vendor:slug}/like', [VendorLikeController::class, 'toggle']);

        // Wishlist paket
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/packages/{package:slug}/wishlist', [WishlistController::class, 'toggle']);

        // Validasi kode promo
        Route::post('/promo/validate', [PromoController::class, 'validateCode'])
            ->middleware('throttle:30,1');

        // Chat dengan admin/vendor
        Route::get('/chats', [ChatController::class, 'index']);
        Route::post('/chats', [ChatController::class, 'store'])
            ->middleware('throttle:10,1');
        Route::get('/chats/{token}', [ChatController::class, 'show']);
        Route::post('/chats/{token}/messages', [ChatController::class, 'send'])
            ->middleware('throttle:30,1');

        // Join Vendor — submit & status (butuh login)
        Route::prefix('join-vendor')->group(function () {
            Route::get('/status', [JoinVendorController::class, 'status']);
            Route::post('/',      [JoinVendorController::class, 'store'])->middleware('throttle:5,1');
        });

        // Vendor dashboard
        Route::prefix('vendor')->group(function () {
            Route::get('/packages', [VendorAdminController::class, 'vendorPackages']);
            Route::get('/bookings', [VendorAdminController::class, 'vendorBookings']);
            Route::get('/payments', [VendorAdminController::class, 'vendorPayments']);
            Route::get('/chats', [VendorAdminController::class, 'vendorChats']);
        });

        // Customer dashboard
        Route::prefix('customer')->group(function () {
            // ── Read-only (GET) — tidak perlu rate limit ketat
            Route::get('/dashboard',                   [CustomerController::class, 'dashboard']);
            Route::get('/wedding-info',                [CustomerController::class, 'weddingInfo']);
            Route::get('/family-members',              [CustomerController::class, 'familyMembers']);
            Route::get('/vip-guests',                  [CustomerController::class, 'vipGuests']);
            Route::get('/notifications',               [CustomerController::class, 'notifications']);
            Route::get('/preparation/sections',        [CustomerController::class, 'preparationSections']);
            Route::get('/preparation/vendors',         [CustomerController::class, 'preparationVendors']);
            Route::get('/payments/upcoming',           [CustomerController::class, 'paymentsUpcoming']);
            Route::get('/payments/schedule',           [CustomerController::class, 'paymentsSchedule']);
            Route::get('/payments/all',                [CustomerController::class, 'paymentsAll']);
            Route::get('/budget',                      [CustomerController::class, 'budget']);
            Route::get('/payment-methods',             [CustomerController::class, 'paymentMethods']);

            // ── Mutasi data — throttle 30 request/menit
            Route::middleware('throttle:30,1')->group(function () {
                Route::put('/wedding-info',                [CustomerController::class, 'updateWeddingInfo']);
                Route::post('/wedding-events',             [CustomerController::class, 'storeWeddingEvent']);
                Route::put('/wedding-events/{id}',         [CustomerController::class, 'updateWeddingEvent']);
                Route::delete('/wedding-events/{id}',      [CustomerController::class, 'destroyWeddingEvent']);

                Route::post('/family-members',             [CustomerController::class, 'storeFamilyMember']);
                Route::put('/family-members/{id}',         [CustomerController::class, 'updateFamilyMember']);
                Route::delete('/family-members/{id}',      [CustomerController::class, 'destroyFamilyMember']);

                Route::post('/vip-guests',                 [CustomerController::class, 'storeVipGuest']);
                Route::post('/vip-guests/import',          [CustomerController::class, 'importVipGuests']);
                Route::put('/vip-guests/{id}',             [CustomerController::class, 'updateVipGuest']);
                Route::delete('/vip-guests/{id}',          [CustomerController::class, 'destroyVipGuest']);

                Route::patch('/notifications/{id}/read',   [CustomerController::class, 'markNotificationRead']);

                Route::post('/preparation/sections',       [CustomerController::class, 'storePreparationSection']);
                Route::delete('/preparation/sections/{id}',[CustomerController::class, 'destroyPreparationSection']);
                Route::post('/preparation/tasks',          [CustomerController::class, 'storePreparationTask']);
                Route::put('/preparation/tasks/{id}',      [CustomerController::class, 'updatePreparationTask']);
                Route::delete('/preparation/tasks/{id}',   [CustomerController::class, 'destroyPreparationTask']);

                Route::post('/payment-methods',            [CustomerController::class, 'storePaymentMethod']);
                Route::delete('/payment-methods/{id}',     [CustomerController::class, 'destroyPaymentMethod']);

                Route::put('/profile',                     [CustomerController::class, 'updateProfile']);
            });

            // ── Ganti password — throttle ketat 5 request/menit
            Route::put('/password', [CustomerController::class, 'updatePassword'])
                ->middleware('throttle:5,1');
        });

        // Admin dashboard
        Route::prefix('admin')->group(function () {
            Route::get('/stats', [VendorAdminController::class, 'adminStats']);
            Route::get('/bookings', [VendorAdminController::class, 'adminBookings']);
            Route::get('/vendors', [VendorAdminController::class, 'adminVendors']);
            Route::get('/vendor-applications', [VendorAdminController::class, 'adminApplications']);
            Route::get('/payments', [VendorAdminController::class, 'adminPayments']);
            Route::get('/chats', [VendorAdminController::class, 'adminChats']);
            Route::get('/vendor-chats', [VendorAdminController::class, 'adminVendorChats']);
        });
    });
});
