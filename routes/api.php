<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\PromoController;
use App\Http\Controllers\Api\V1\RealWeddingController;
use App\Http\Controllers\Api\V1\ReviewController;
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

    Route::get('/real-weddings/{realWedding:slug}', [RealWeddingController::class, 'show']);

    // Autentikasi — menghasilkan token Sanctum untuk aplikasi iOS
    Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/apple', [AuthController::class, 'apple']);
        Route::post('/google', [AuthController::class, 'google']);
    });

    // Terproteksi — butuh token Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
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
    });
});
