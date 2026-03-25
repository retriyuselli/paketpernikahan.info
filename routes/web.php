<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.home');
})->name('home');

Route::get('/vendor', function () {
    $q        = request('q');
    $catSlug  = request('category');
    $city     = request('city');
    $price    = request('price');

    $query = \App\Models\Vendor::where('is_active', true)
        ->withCount('galleries')
        ->with([
            'galleries'       => fn ($q) => $q->where('is_cover', true),
            'cheapestPackage',
        ])
        ->orderByDesc('rating');

    if ($q) {
        $query->where(fn ($w) =>
            $w->where('name', 'like', "%{$q}%")
              ->orWhere('type', 'like', "%{$q}%")
              ->orWhere('location', 'like', "%{$q}%")
        );
    }

    if ($catSlug) {
        $query->where('category', $catSlug);
    }

    if ($city) {
        $query->where('city', 'like', "%{$city}%");
    }

    if ($price) {
        [$min, $max] = explode('-', $price);
        $query->whereBetween('price_start', [(int)$min, (int)$max]);
    }

    $vendors = $query->get()->groupBy('category');

    $provinces = \App\Models\Vendor::where('is_active', true)
        ->whereNotNull('province')
        ->distinct()
        ->orderBy('province')
        ->pluck('province');

    // Group cities by province for cascade dropdown
    $citiesByProvince = \App\Models\Vendor::where('is_active', true)
        ->whereNotNull('province')
        ->whereNotNull('city')
        ->select('province', 'city')
        ->distinct()
        ->orderBy('city')
        ->get()
        ->groupBy('province')
        ->map(fn ($rows) => $rows->pluck('city')->unique()->sort()->values());

    $categoriesWithVendors = \App\Models\CategoryVendor::where('is_active', true)
        ->orderBy('sort_order')
        ->get()
        ->map(function ($cat) use ($vendors) {
            $cat->vendors = $vendors->get($cat->slug, collect());
            return $cat;
        })
        ->filter(fn ($cat) => $cat->vendors->isNotEmpty());

    return view('front.vendor', [
        'categories'       => $categoriesWithVendors,
        'provinces'        => $provinces,
        'citiesByProvince' => $citiesByProvince,
    ]);
})->name('vendor');

Route::get('/vendor/{vendor:slug}', function (\App\Models\Vendor $vendor) {
    $vendor->load(['galleries', 'packages', 'approvedReviews', 'cheapestPackage']);
    return view('vendor.detail', compact('vendor'));
})->name('vendor.detail');

Route::post('/vendor/{vendor:slug}/reviews', [\App\Http\Controllers\VendorReviewController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.review.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/vendor/{vendor:slug}/edit', [\App\Http\Controllers\VendorEditController::class, 'edit'])
        ->name('vendor.edit');
    Route::post('/vendor/{vendor:slug}/update', [\App\Http\Controllers\VendorEditController::class, 'update'])
        ->name('vendor.update');

    Route::post('/vendor/{vendor:slug}/packages', [\App\Http\Controllers\VendorPackageController::class, 'store'])
        ->name('vendor.packages.store');
    Route::put('/vendor/{vendor:slug}/packages/{package}', [\App\Http\Controllers\VendorPackageController::class, 'update'])
        ->name('vendor.packages.update');
    Route::delete('/vendor/{vendor:slug}/packages/{package}', [\App\Http\Controllers\VendorPackageController::class, 'destroy'])
        ->name('vendor.packages.destroy');

    Route::post('/vendor/{vendor:slug}/gallery', [\App\Http\Controllers\VendorGalleryController::class, 'store'])
        ->name('vendor.gallery.store');
    Route::post('/vendor/{vendor:slug}/gallery/{gallery}', [\App\Http\Controllers\VendorGalleryController::class, 'update'])
        ->name('vendor.gallery.update');
    Route::delete('/vendor/{vendor:slug}/gallery/{gallery}', [\App\Http\Controllers\VendorGalleryController::class, 'destroy'])
        ->name('vendor.gallery.destroy');
});

// AuthRedirection
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::get('/dashboard', function () {
    $user        = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    return view('dashboard.index', compact('user', 'reviewCount'));
})->name('dashboard')->middleware('auth');

Route::get('/dashboard/pengaturan', function () {
    $user = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    return view('dashboard.pengaturan', compact('user', 'reviewCount'));
})->name('dashboard.pengaturan')->middleware('auth');

Route::get('/dashboard/ulasan', function () {
    $user        = auth()->user();
    $myReviews   = \App\Models\VendorReview::where('user_id', $user->id)->latest()->with('vendor')->get();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    return view('dashboard.ulasan', compact('user', 'myReviews', 'reviewCount'));
})->name('dashboard.ulasan')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/dashboard/profile/name', [\App\Http\Controllers\ProfileController::class, 'updateName'])
        ->name('dashboard.profile.update');
    Route::post('/dashboard/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('dashboard.password.update');
    Route::post('/dashboard/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])
        ->name('dashboard.avatar.update');
});
