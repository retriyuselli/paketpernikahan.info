<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\VendorApplicationController;
use App\Http\Controllers\VendorApplicationAdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $heroCircles = \App\Models\HeroCircle::active()->get();
    return view('front.home', compact('heroCircles'));
})->name('home');

Route::get('/join-vendor/signup', function () {
    if (Auth::check()) {
        $user = \App\Models\User::find(Auth::id());
        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->route('join.vendor');
        }
    }
    return view('front.join-vendor-signup');
})->name('join.vendor.signup');

Route::get('/join-vendor', [VendorApplicationController::class, 'create'])
    ->name('join.vendor')
    ->middleware(['auth', 'verified']);

Route::get('/join-vendor/cities', [VendorApplicationController::class, 'cities'])
    ->name('join.vendor.cities')
    ->middleware(['auth', 'verified']);

Route::post('/join-vendor', [VendorApplicationController::class, 'store'])
    ->name('join.vendor.store')
    ->middleware(['auth', 'verified']);

Route::get('/store', function () {
    $categories = \App\Models\CategoryVendor::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $packages = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
        ->with([
            'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'price_start', 'discount', 'cover_image'),
        ])
        ->orderBy('sort_order')
        ->orderBy('price_raw')
        ->get();

    $activeCategorySlugs = $categories->pluck('slug')->filter()->all();
    $packagesByCategory = collect();
    foreach ($packages as $p) {
        if (!$p->vendor) {
            continue;
        }
        $vendorCats = is_array($p->vendor->categories) && count($p->vendor->categories)
            ? $p->vendor->categories
            : ($p->vendor->category ? [$p->vendor->category] : []);
        foreach ($vendorCats as $slug) {
            if (!$slug || !in_array($slug, $activeCategorySlugs, true)) {
                continue;
            }
            $packagesByCategory[$slug] = ($packagesByCategory[$slug] ?? collect())->push($p);
        }
    }

    $uncategorizedPackages = $packages->filter(function ($p) use ($activeCategorySlugs) {
        if (!$p->vendor) {
            return true;
        }
        $vendorCats = is_array($p->vendor->categories) && count($p->vendor->categories)
            ? $p->vendor->categories
            : ($p->vendor->category ? [$p->vendor->category] : []);
        foreach ($vendorCats as $slug) {
            if ($slug && in_array($slug, $activeCategorySlugs, true)) {
                return false;
            }
        }
        return true;
    });

    return view('front.store', compact('categories', 'packagesByCategory', 'uncategorizedPackages'));
})->name('store');

Route::get('/store/kategori/{category:slug}', function (\App\Models\CategoryVendor $category) {
    abort_unless($category->is_active, 404);

    $sort = request()->query('sort', 'rekomendasi');
    $allowedSort = ['rekomendasi', 'termurah', 'termahal', 'terbaru'];
    if (!in_array($sort, $allowedSort, true)) {
        $sort = 'rekomendasi';
    }

    $q = trim((string) request()->query('q', ''));

    $packagesQuery = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($q) => $q
            ->where('is_active', true)
            ->where('is_profile_complete', true)
            ->where(fn ($w) => $w
                ->where('category', $category->slug)
                ->orWhereJsonContains('categories', $category->slug)
            )
        )
        ->with([
            'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'city', 'location', 'price_start', 'discount', 'cover_image'),
        ]);

    if ($q !== '') {
        $packagesQuery->where(function ($qq) use ($q) {
            $qq->where('name', 'like', '%' . $q . '%')
                ->orWhereHas('vendor', function ($vq) use ($q) {
                    $vq->where('name', 'like', '%' . $q . '%')
                        ->orWhere('city', 'like', '%' . $q . '%')
                        ->orWhere('location', 'like', '%' . $q . '%');
                });
        });
    }

    $packagesQuery = match ($sort) {
        'termurah' => $packagesQuery->orderBy('price_raw')->orderBy('sort_order'),
        'termahal' => $packagesQuery->orderByDesc('price_raw')->orderBy('sort_order'),
        'terbaru' => $packagesQuery->orderByDesc('created_at')->orderBy('sort_order'),
        default => $packagesQuery->orderBy('sort_order')->orderBy('price_raw'),
    };

    $packages = $packagesQuery->paginate(24)->withQueryString();

    return view('store.store-category', compact('category', 'packages', 'sort', 'q'));
})->name('store.category');

Route::get('/store/paket/{package}', function (\App\Models\VendorPackage $package) {
    abort_unless($package->is_active, 404);
    $package->load([
        'vendor' => fn ($q) => $q->with([
            'galleries' => fn ($g) => $g->orderBy('sort_order'),
            'categoryVendor' => fn ($c) => $c->select('id', 'slug', 'name', 'sort_order', 'is_active', 'color', 'icon', 'description'),
        ]),
    ]);

    $vendor = $package->vendor;
    abort_unless($vendor && $vendor->is_active && $vendor->is_profile_complete, 404);

    $images = collect($vendor->galleries)
        ->pluck('image_url')
        ->filter()
        ->values();

    if ($images->isEmpty() && is_array($vendor->cover_image ?? null)) {
        $images = collect($vendor->cover_image)
            ->filter()
            ->map(function ($path) {
                if (!$path) {
                    return null;
                }
                if (is_string($path) && str_starts_with($path, 'http')) {
                    return $path;
                }
                if (is_string($path)) {
                    return \Illuminate\Support\Facades\Storage::url($path);
                }
                return null;
            })
            ->filter()
            ->values();
    }

    $images = $images->take(6);

    $otherPackages = \App\Models\VendorPackage::query()
        ->where('vendor_id', $vendor->id)
        ->where('is_active', true)
        ->whereKeyNot($package->id)
        ->orderBy('sort_order')
        ->orderBy('price_raw')
        ->get();

    return view('store.store-detail', compact('package', 'vendor', 'images', 'otherPackages'));
})->name('store.package.show');

Route::get('/vendor', function () {
    $q        = request('q');
    $catSlug  = request('category');
    $city     = request('city');
    $price    = request('price');

    $query = \App\Models\Vendor::where('is_active', true)
        ->where('is_profile_complete', true)
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
        $query->where(fn ($w) => $w
            ->where('category', $catSlug)
            ->orWhereJsonContains('categories', $catSlug)
        );
    }

    if ($city) {
        $query->where('city', 'like', "%{$city}%");
    }

    if ($price) {
        [$min, $max] = explode('-', $price);
        $query->whereBetween('price_start', [(int)$min, (int)$max]);
    }

    $badgeSourceQuery = \App\Models\Vendor::where('is_active', true)
        ->where('is_profile_complete', true);

    $terlarisIds = (clone $badgeSourceQuery)
        ->withCount([
            'bookings as pengunjung_bookings_count' => fn ($q) => $q->whereHas(
                'user',
                fn ($u) => $u->role('pengunjung')
            ),
        ])
        ->orderByDesc('pengunjung_bookings_count')
        ->limit(10)
        ->pluck('id')
        ->all();

    $topRatedIds = (clone $badgeSourceQuery)
        ->withCount(['approvedReviews as approved_reviews_count'])
        ->orderByDesc('approved_reviews_count')
        ->limit(10)
        ->pluck('id')
        ->all();

    $terlarisMap = array_fill_keys($terlarisIds, true);
    $topRatedMap = array_fill_keys($topRatedIds, true);
    $baruSince = now()->subDays(30);

    $vendorsRaw = $query->get();
    $vendorsRaw->each(function ($vendor) use ($terlarisMap, $topRatedMap, $baruSince) {
        $badges = array_values(array_unique(array_filter((array) ($vendor->badge ?? []))));

        if (isset($terlarisMap[$vendor->id])) {
            $badges[] = 'terlaris';
        }

        if (isset($topRatedMap[$vendor->id])) {
            $badges[] = 'top_rated';
        }

        if ($vendor->created_at && $vendor->created_at->gte($baruSince)) {
            $badges[] = 'baru';
        }

        $vendor->setAttribute('badge', array_values(array_unique($badges)));
    });

    $vendorsByCategory = collect();
    foreach ($vendorsRaw as $vendor) {
        $vendorCats = $catSlug
            ? [$catSlug]
            : (is_array($vendor->categories) && count($vendor->categories)
                ? $vendor->categories
                : ($vendor->category ? [$vendor->category] : []));
        foreach ($vendorCats as $slug) {
            if (!$slug) {
                continue;
            }
            $vendorsByCategory[$slug] = ($vendorsByCategory[$slug] ?? collect())->push($vendor);
        }
    }

    $provinces = \App\Models\Vendor::where('is_active', true)
        ->where('is_profile_complete', true)
        ->whereNotNull('province')
        ->distinct()
        ->orderBy('province')
        ->pluck('province');

    // Group cities by province for cascade dropdown
    $citiesByProvince = \App\Models\Vendor::where('is_active', true)
        ->where('is_profile_complete', true)
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
        ->map(function ($cat) use ($vendorsByCategory) {
            $cat->vendors = $vendorsByCategory->get($cat->slug, collect());
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
    $authUser = Auth::check() ? \App\Models\User::find(Auth::id()) : null;
    $isPrivileged = $authUser?->hasRole(['super_admin', 'admin']) ?? false;
    $isVendorOwner = $authUser?->hasRole(['vendor']) && (int) $vendor->owner_user_id === (int) $authUser->id;
    abort_if(!$vendor->is_active && !($isPrivileged || $isVendorOwner), 404);
    $vendorDetailDisabled = !$vendor->is_profile_complete && !$isPrivileged;
    $vendorDetailBackUrl = $isVendorOwner || $isPrivileged ? route('vendor.edit', $vendor) : route('vendor');

    $vendor->load(['galleries', 'packages', 'approvedReviews', 'cheapestPackage']);
    $vendor->loadCount([
        'approvedReviews as comments_count',
        'likedByUsers as likes',
    ]);
    $vendor->loadAvg('approvedReviews as rating', 'rating');
    $hasLiked = $authUser?->likedVendors()->where('vendor_id', $vendor->id)->exists() ?? false;
    $myBooking = $authUser
        ? \App\Models\VendorBooking::where('vendor_id', $vendor->id)
            ->where('user_id', $authUser->id)
            ->latest()
            ->first()
        : null;
    return view('vendor.detail', compact('vendor', 'hasLiked', 'myBooking', 'vendorDetailDisabled', 'vendorDetailBackUrl'));
})->name('vendor.detail');

Route::post('/vendor/{vendor:slug}/reviews', [\App\Http\Controllers\VendorReviewController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.review.store');

Route::post('/vendor/reviews/{review}/reply', [\App\Http\Controllers\VendorReviewReplyController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.review.reply');

Route::post('/vendor/{vendor:slug}/like', [\App\Http\Controllers\VendorLikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('vendor.like');

Route::post('/vendor/{vendor:slug}/bookings', [\App\Http\Controllers\VendorBookingController::class, 'store'])
    ->middleware('auth')
    ->name('vendor.booking.store');

Route::get('/booking/vendor/{vendor:slug}', function (\App\Models\Vendor $vendor) {
    $authUser = Auth::check() ? \App\Models\User::find(Auth::id()) : null;
    $isPrivileged = $authUser?->hasRole(['super_admin', 'admin']) ?? false;
    $isVendorOwner = $authUser?->hasRole(['vendor']) && (int) $vendor->owner_user_id === (int) $authUser->id;
    abort_if(!$vendor->is_active && !($isPrivileged || $isVendorOwner), 404);

    $vendorBookingDisabled = !$vendor->is_profile_complete && !$isPrivileged;
    $vendorBookingBackUrl = $isVendorOwner || $isPrivileged ? route('vendor.edit', $vendor) : route('vendor.detail', $vendor);

    $packagesQuery = $vendor->packages()->orderBy('sort_order')->orderBy('price_raw');
    if (!($isPrivileged || $isVendorOwner)) {
        $packagesQuery->where('is_active', true);
    }
    $packages = $packagesQuery->get();

    $selectedPackageId = (int) request()->query('vendor_package_id', 0);
    $selectedPackage = $selectedPackageId ? $packages->firstWhere('id', $selectedPackageId) : null;

    return view('booking.create', compact('vendor', 'packages', 'selectedPackage', 'vendorBookingDisabled', 'vendorBookingBackUrl'));
})->name('booking.vendor');

Route::get('/booking/paket/{package}', function (\App\Models\VendorPackage $package) {
    abort_unless($package->is_active, 404);

    $package->load([
        'vendor' => fn ($q) => $q->select('id', 'owner_user_id', 'name', 'slug', 'type', 'category', 'categories', 'city', 'is_active', 'is_profile_complete'),
    ]);

    $vendor = $package->vendor;
    abort_unless($vendor && $vendor->is_active, 404);

    $authUser = Auth::check() ? \App\Models\User::find(Auth::id()) : null;
    $isPrivileged = $authUser?->hasRole(['super_admin', 'admin']) ?? false;
    $isVendorOwner = $authUser?->hasRole(['vendor']) && (int) $vendor->owner_user_id === (int) $authUser->id;

    $vendorBookingDisabled = !$vendor->is_profile_complete && !$isPrivileged;
    $vendorBookingBackUrl = $isVendorOwner || $isPrivileged ? route('vendor.edit', $vendor) : route('store.package.show', $package);

    $packagesQuery = $vendor->packages()->orderBy('sort_order')->orderBy('price_raw');
    if (!($isPrivileged || $isVendorOwner)) {
        $packagesQuery->where('is_active', true);
    }
    $packages = $packagesQuery->get();

    $selectedPackage = $packages->firstWhere('id', $package->id);

    return view('booking.create', compact('vendor', 'packages', 'selectedPackage', 'vendorBookingDisabled', 'vendorBookingBackUrl'));
})->name('booking.package');

Route::middleware(['auth'])->group(function () {
    Route::get('/vendor/{vendor:slug}/edit', [\App\Http\Controllers\VendorEditController::class, 'edit'])
        ->name('vendor.edit');
    Route::post('/vendor/{vendor:slug}/update', [\App\Http\Controllers\VendorEditController::class, 'update'])
        ->name('vendor.update');

    Route::post('/vendor/{vendor:slug}/packages', [\App\Http\Controllers\VendorPackageController::class, 'store'])
        ->name('vendor.packages.store');
    Route::get('/vendor/{vendor:slug}/packages/{package}/edit', [\App\Http\Controllers\VendorPackageController::class, 'edit'])
        ->name('vendor.packages.edit');
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
    if (request()->filled('redirect')) {
        $r = request('redirect');
        $host = parse_url($r, PHP_URL_HOST);
        if (!$host && str_starts_with($r, '/')) {
            request()->session()->put('url.intended', $r);
        }
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/register', function () {
    if (request()->filled('redirect')) {
        $r = request('redirect');
        $host = parse_url($r, PHP_URL_HOST);
        if (!$host && str_starts_with($r, '/')) {
            request()->session()->put('url.intended', $r);
        }
    }
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Email Verification
Route::get('/email/verify', function () {
    $user = \App\Models\User::find(Auth::id());
    if ($user?->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->intended(route('dashboard'))->with('verified', true);
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
    $user        = \App\Models\User::findOrFail(Auth::id());
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;
    return view('dashboard.index', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount'));
})->name('dashboard')->middleware(['auth', 'verified']);

Route::get('/dashboard/pengaturan', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;
    return view('dashboard.pengaturan', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount'));
})->name('dashboard.pengaturan')->middleware(['auth', 'verified']);

Route::get('/dashboard/ulasan', function () {
    $user        = \App\Models\User::findOrFail(Auth::id());
    $myReviews   = \App\Models\VendorReview::where('user_id', $user->id)->latest()->with('vendor')->get();
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;
    $reviewFilter = request('review_filter');
    $pendingReviewCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorReview::where('is_approved', false)->count()
        : 0;

    $allReviews = collect();
    if ($user->hasRole(['super_admin', 'admin'])) {
        $q = \App\Models\VendorReview::latest()->with(['vendor', 'user']);
        if ($reviewFilter === 'pending') {
            $q->where('is_approved', false);
        }
        $allReviews = $q->limit(200)->get();
    }

    return view('dashboard.ulasan', compact('user', 'myReviews', 'allReviews', 'pendingReviewCount', 'reviewFilter', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount'));
})->name('dashboard.ulasan')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookings = $user->vendorBookings()
        ->with(['vendor', 'vendorPackage'])
        ->latest()
        ->get();
    $bookingCount = $bookings->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;
    return view('dashboard.booking', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'bookings'));
})->name('dashboard.booking')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking/{booking}/edit', function (\App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($booking->user_id === $user->id, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $booking->load(['vendor', 'vendorPackage', 'vendor.packages']);

    return view('dashboard.booking-edit', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'booking'));
})->name('dashboard.booking.edit')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking/{booking}/payment', function (\App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($booking->user_id === $user->id, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $booking->load(['vendor', 'vendorPackage', 'payments' => fn ($q) => $q->latest()]);

    return view('dashboard.booking-payment', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'booking'));
})->name('dashboard.booking.payment')->middleware(['auth', 'verified']);

Route::get('/dashboard/booking/{booking}/invoice', function (\App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($booking->user_id === $user->id, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $booking->load(['vendor', 'vendorPackage', 'payments' => fn ($q) => $q->latest()]);

    return view('dashboard.booking-invoice', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'booking'));
})->name('dashboard.booking.invoice')->middleware(['auth', 'verified']);

Route::post('/dashboard/booking/{booking}/payment', [\App\Http\Controllers\VendorBookingPaymentController::class, 'store'])
    ->name('dashboard.booking.payment.store')
    ->middleware(['auth', 'verified']);

Route::get('/dashboard/booking-user', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = \App\Models\VendorBooking::where('status', 'pending')->count();

    $bookings = \App\Models\VendorBooking::with(['user', 'vendor', 'vendorPackage'])
        ->latest()
        ->limit(200)
        ->get();

    return view('dashboard.booking-user', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'bookings'));
})->name('dashboard.booking.user')->middleware(['auth', 'verified']);

Route::get('/dashboard/payment-user', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = \App\Models\VendorBooking::where('status', 'pending')->count();

    $vendorId = request('vendor_id');
    $status = request('status');
    $pendingPaymentCount = \App\Models\VendorBookingPayment::where('status', 'pending_verification')->count();

    $vendors = \App\Models\Vendor::orderBy('name')->limit(200)->get(['id', 'name']);

    $payments = \App\Models\VendorBookingPayment::with(['booking.vendor', 'booking.user', 'booking.vendorPackage'])
        ->when($vendorId, fn ($q) => $q->whereHas('booking', fn ($b) => $b->where('vendor_id', $vendorId)))
        ->when($status, fn ($q) => $q->where('status', $status))
        ->latest()
        ->limit(300)
        ->get();

    return view('dashboard.payment-user', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'payments', 'vendors', 'vendorId', 'status', 'pendingPaymentCount'));
})->name('dashboard.payment.user')->middleware(['auth', 'verified']);

Route::get('/dashboard/vendor-applications', [VendorApplicationAdminController::class, 'index'])
    ->name('dashboard.vendor.applications')
    ->middleware(['auth', 'verified']);

Route::post('/dashboard/vendor-applications/{application}/approve', [VendorApplicationAdminController::class, 'approve'])
    ->name('dashboard.vendor.applications.approve')
    ->middleware(['auth', 'verified']);

Route::post('/dashboard/vendor-applications/{application}/reject', [VendorApplicationAdminController::class, 'reject'])
    ->name('dashboard.vendor.applications.reject')
    ->middleware(['auth', 'verified']);

Route::get('/dashboard/vendor/bookings', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin', 'vendor']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $vendorIds = $user->hasRole(['super_admin', 'admin'])
        ? null
        : \App\Models\Vendor::where('owner_user_id', $user->id)->pluck('id');

    $bookings = \App\Models\VendorBooking::with(['user', 'vendor', 'vendorPackage'])
        ->when($vendorIds, fn ($q) => $q->whereIn('vendor_id', $vendorIds))
        ->latest()
        ->limit(200)
        ->get();

    return view('dashboard.vendor-bookings', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'bookings'));
})->name('dashboard.vendor.bookings')->middleware(['auth', 'verified']);

Route::get('/dashboard/vendor/bookings/{booking}', function (\App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin', 'vendor']), 403);

    $booking->load(['vendor', 'user', 'vendorPackage', 'payments' => fn ($q) => $q->latest()]);

    $isAdmin = $user->hasRole(['super_admin', 'admin']);
    $isVendorOwner = $user->hasRole(['vendor']) && (int) $booking->vendor?->owner_user_id === (int) $user->id;
    abort_unless($isAdmin || $isVendorOwner, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    return view('dashboard.vendor-booking-edit', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'booking'));
})->name('dashboard.vendor.bookings.show')->middleware(['auth', 'verified']);

Route::put('/dashboard/vendor/bookings/{booking}', function (\Illuminate\Http\Request $request, \App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin', 'vendor']), 403);

    $booking->load(['vendor', 'vendorPackage']);

    $isAdmin = $user->hasRole(['super_admin', 'admin']);
    $isVendorOwner = $user->hasRole(['vendor']) && (int) $booking->vendor?->owner_user_id === (int) $user->id;
    abort_unless($isAdmin || $isVendorOwner, 403);

    $data = $request->validateWithBag('vendor_booking', [
        'status' => ['required', 'in:pending,contacted,confirmed,done,no_response,cancelled'],
        'dp_required_amount' => ['nullable', 'integer', 'min:0'],
    ]);

    $agreedTotal = $booking->vendorPackage ? (int) ($booking->vendorPackage->price_raw ?? 0) : null;

    $booking->update([
        'status' => $data['status'],
        'agreed_total' => $agreedTotal,
        'dp_required_amount' => $data['dp_required_amount'] ?? null,
    ]);

    return back()->with('vendor_booking_success', 'Booking berhasil diperbarui.');
})->name('dashboard.vendor.bookings.update')->middleware(['auth', 'verified']);

Route::delete('/dashboard/vendor/bookings/{booking}', function (\App\Models\VendorBooking $booking) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin']), 403);

    $booking->delete();

    return redirect()
        ->route('dashboard.vendor.bookings')
        ->with('vendor_booking_success', 'Booking berhasil dihapus.');
})->name('dashboard.vendor.bookings.destroy')->middleware(['auth', 'verified']);

Route::get('/dashboard/vendor/payments', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin', 'vendor']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $vendorIds = $user->hasRole(['super_admin', 'admin'])
        ? null
        : \App\Models\Vendor::where('owner_user_id', $user->id)->pluck('id');

    $paymentFilter = request('payment_filter');
    $pendingPaymentCount = \App\Models\VendorBookingPayment::query()
        ->where('status', 'pending_verification')
        ->when($vendorIds, fn ($q) => $q->whereHas('booking', fn ($b) => $b->whereIn('vendor_id', $vendorIds)))
        ->count();

    $payments = \App\Models\VendorBookingPayment::with(['booking.vendor', 'booking.user', 'booking.vendorPackage'])
        ->when($vendorIds, fn ($q) => $q->whereHas('booking', fn ($b) => $b->whereIn('vendor_id', $vendorIds)))
        ->when($paymentFilter === 'pending', fn ($q) => $q->where('status', 'pending_verification'))
        ->latest()
        ->limit(200)
        ->get();

    return view('dashboard.vendor-payments', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'payments', 'pendingPaymentCount', 'paymentFilter'));
})->name('dashboard.vendor.payments')->middleware(['auth', 'verified']);

Route::get('/dashboard/vendor/vendors', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin', 'vendor']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;

    $vendors = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\Vendor::orderBy('name')->limit(200)->get()
        : \App\Models\Vendor::where('owner_user_id', $user->id)->orderBy('name')->get();

    return view('dashboard.vendor-vendors', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'vendors'));
})->name('dashboard.vendor.vendors')->middleware(['auth', 'verified']);

Route::get('/dashboard/admin/vendors', function () {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin']), 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = \App\Models\VendorBooking::where('status', 'pending')->count();

    $q = trim((string) request('q', ''));

    $vendors = \App\Models\Vendor::query()
        ->with('owner')
        ->when($q !== '', fn ($query) => $query->where(fn ($w) =>
            $w->where('name', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('category', 'like', "%{$q}%")
                ->orWhere('city', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
        ))
        ->orderBy('name')
        ->get();

    return view('dashboard.admin-vendors', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'vendors', 'q'));
})->name('dashboard.admin.vendors')->middleware(['auth', 'verified']);

Route::post('/dashboard/admin/vendors/{vendor}/toggle-active', function (\Illuminate\Http\Request $request, \App\Models\Vendor $vendor) {
    $user = \App\Models\User::findOrFail(Auth::id());
    abort_unless($user->hasRole(['super_admin', 'admin']), 403);

    $vendor->update([
        'is_active' => !$vendor->is_active,
    ]);

    return back()->with('success', 'Status vendor berhasil diperbarui.');
})->name('dashboard.admin.vendors.toggle')->middleware(['auth', 'verified']);

Route::post('/dashboard/vendor/payments/{payment}/verify', [\App\Http\Controllers\VendorBookingPaymentController::class, 'verify'])
    ->name('dashboard.vendor.payments.verify')
    ->middleware(['auth', 'verified']);

Route::put('/dashboard/booking/{booking}', function (\Illuminate\Http\Request $request, \App\Models\VendorBooking $booking) {
    $user = $request->user();
    abort_unless($booking->user_id === $user->id, 403);

    if ($booking->status !== 'pending') {
        return redirect()->route('dashboard.booking')->with('booking_error', 'Booking tidak bisa diedit karena statusnya sudah diproses.');
    }

    $vendorId = $booking->vendor_id;
    $hasPackages = \App\Models\VendorPackage::where('vendor_id', $vendorId)->exists();

    $data = $request->validateWithBag('booking', [
        'vendor_package_id' => [
            $hasPackages ? 'required' : 'nullable',
            'integer',
            \Illuminate\Validation\Rule::exists('vendor_packages', 'id')->where('vendor_id', $vendorId),
        ],
        'event_date' => ['required', 'date', 'after_or_equal:today'],
        'phone'      => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s.-]{8,30}$/'],
        'notes'      => ['nullable', 'string', 'max:2000'],
    ]);

    $normalizedPhone = \App\Models\VendorBooking::normalizeWhatsappNumber($data['phone']);
    if (strlen($normalizedPhone) < 10 || strlen($normalizedPhone) > 15 || !str_starts_with($normalizedPhone, '62')) {
        return back()
            ->withErrors(['phone' => 'Nomor WhatsApp tidak valid.'], 'booking')
            ->withInput();
    }

    $booking->update([
        'vendor_package_id' => $data['vendor_package_id'] ?? null,
        'event_date'        => $data['event_date'],
        'phone'             => $normalizedPhone,
        'notes'             => $data['notes'] ?? null,
    ]);

    return redirect()->route('dashboard.booking')->with('booking_success', 'Booking berhasil diperbarui.');
})->name('dashboard.booking.update')->middleware(['auth', 'verified']);

Route::get('/dashboard/favorit', function () {
    $user        = \App\Models\User::findOrFail(Auth::id());
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
    $bookingUserCount = $user->hasRole(['super_admin', 'admin'])
        ? \App\Models\VendorBooking::where('status', 'pending')->count()
        : 0;
    return view('dashboard.favorit', compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount', 'likedVendors'));
})->name('dashboard.favorit')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/profile/name', [\App\Http\Controllers\ProfileController::class, 'updateName'])
        ->name('dashboard.profile.update');
    Route::post('/dashboard/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('dashboard.password.update');
    Route::post('/dashboard/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])
        ->name('dashboard.avatar.update');
    Route::post('/dashboard/profile/whatsapp', [\App\Http\Controllers\ProfileController::class, 'updateWhatsapp'])
        ->name('dashboard.whatsapp.update');
});
