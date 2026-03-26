<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $heroCircles = \App\Models\HeroCircle::active()->get();
    return view('front.home', compact('heroCircles'));
})->name('home');

Route::get('/vendor', function () {
    $q        = request('q');
    $catSlug  = request('category');
    $city     = request('city');
    $price    = request('price');

    $query = \App\Models\Vendor::where('is_active', true)
        ->withCount('galleries')
        ->withCount([
            'approvedReviews as comments_count',
            'likedByUsers as likes',
        ])
        ->withAvg('approvedReviews as rating', 'rating')
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
    $vendor->loadCount([
        'approvedReviews as comments_count',
        'likedByUsers as likes',
    ]);
    $vendor->loadAvg('approvedReviews as rating', 'rating');
    $hasLiked = auth()->check() && auth()->user()->likedVendors()->where('vendor_id', $vendor->id)->exists();
    return view('vendor.detail', compact('vendor', 'hasLiked'));
})->name('vendor.detail');

Route::post('/vendor/{vendor:slug}/reviews', [\App\Http\Controllers\VendorReviewController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.review.store');

Route::post('/vendor/{vendor:slug}/like', [\App\Http\Controllers\VendorLikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('vendor.like');

Route::post('/vendor/{vendor:slug}/bookings', [\App\Http\Controllers\VendorBookingController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.booking.store');

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

Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Email Verification
Route::get('/email/verify', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = \Illuminate\Support\Facades\Password::sendResetLink($request->only('email'));
    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (\App\Models\User $user, string $password) {
            $user->forceFill(['password' => \Illuminate\Support\Facades\Hash::make($password)])->save();
            event(new \Illuminate\Auth\Events\PasswordReset($user));
        }
    );
    return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');

Route::get('/dashboard', function () {
    $user        = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    return view('dashboard.index', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount'));
})->name('dashboard')->middleware(['auth', 'verified']);

Route::get('/dashboard/pengaturan', function () {
    $user = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    return view('dashboard.pengaturan', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount'));
})->name('dashboard.pengaturan')->middleware(['auth', 'verified']);

Route::get('/dashboard/ulasan', function () {
    $user        = auth()->user();
    $myReviews   = \App\Models\VendorReview::where('user_id', $user->id)->latest()->with('vendor')->get();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    return view('dashboard.ulasan', compact('user', 'myReviews', 'reviewCount', 'favoriteCount', 'bookingCount'));
})->name('dashboard.ulasan')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking', function () {
    $user = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookings = $user->vendorBookings()
        ->with(['vendor', 'vendorPackage'])
        ->latest()
        ->get();
    $bookingCount = $bookings->count();
    return view('dashboard.booking', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookings'));
})->name('dashboard.booking')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking/{booking}/edit', function (\App\Models\VendorBooking $booking) {
    $user = auth()->user();
    abort_unless($booking->user_id === $user->id, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();

    $booking->load(['vendor', 'vendorPackage', 'vendor.packages']);

    return view('dashboard.booking-edit', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'booking'));
})->name('dashboard.booking.edit')->middleware(['auth', 'verified']);

Route::put('/dashboard/booking/{booking}', function (\Illuminate\Http\Request $request, \App\Models\VendorBooking $booking) {
    $user = $request->user();
    abort_unless($booking->user_id === $user->id, 403);

    if ($booking->status !== 'pending') {
        return redirect()->route('dashboard.booking')->with('booking_error', 'Booking tidak bisa diedit karena statusnya sudah diproses.');
    }

    $vendorId = $booking->vendor_id;

    $data = $request->validateWithBag('booking', [
        'vendor_package_id' => [
            'nullable',
            'integer',
            \Illuminate\Validation\Rule::exists('vendor_packages', 'id')->where('vendor_id', $vendorId),
        ],
        'event_date' => ['required', 'date', 'after_or_equal:today'],
        'phone'      => ['required', 'string', 'max:30'],
        'notes'      => ['nullable', 'string', 'max:2000'],
    ]);

    $booking->update([
        'vendor_package_id' => $data['vendor_package_id'] ?? null,
        'event_date'        => $data['event_date'],
        'phone'             => $data['phone'],
        'notes'             => $data['notes'] ?? null,
    ]);

    return redirect()->route('dashboard.booking')->with('booking_success', 'Booking berhasil diperbarui.');
})->name('dashboard.booking.update')->middleware(['auth', 'verified']);

Route::get('/dashboard/favorit', function () {
    $user        = auth()->user();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $likedVendors = $user->likedVendors()
        ->with('cheapestPackage')
        ->withCount([
            'approvedReviews as comments_count',
            'likedByUsers as likes',
        ])
        ->withAvg('approvedReviews as rating', 'rating')
        ->latest('vendor_user_likes.created_at')
        ->get();
    $favoriteCount = $likedVendors->count();
    $bookingCount = $user->vendorBookings()->count();
    return view('dashboard.favorit', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'likedVendors'));
})->name('dashboard.favorit')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/profile/name', [\App\Http\Controllers\ProfileController::class, 'updateName'])
        ->name('dashboard.profile.update');
    Route::post('/dashboard/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('dashboard.password.update');
    Route::post('/dashboard/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])
        ->name('dashboard.avatar.update');
});
