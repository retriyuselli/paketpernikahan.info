<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\VendorApplicationController;
use App\Http\Controllers\VendorApplicationAdminController;
use App\Http\Controllers\VendorReviewModerationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $heroCircles = \App\Models\HeroCircle::active()->get();

    $homeCategories = \App\Models\CategoryVendor::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $homePackages = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
        ->with([
            'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'cover_image'),
        ])
        ->orderBy('sort_order')
        ->orderBy('price')
        ->get();

    $activeCategorySlugs = $homeCategories->pluck('slug')->filter()->all();
    $activeCategoryIdToSlug = $homeCategories->pluck('slug', 'id'); // id => slug map

    // Two buckets per slug: explicit (package chose category) vs fallback (inherited from vendor)
    $homePackagesByCategoryExplicit = collect();
    $homePackagesByCategoryFallback = collect();

    foreach ($homePackages as $p) {
        if (!$p->vendor) continue;
        $catIds = is_array($p->category_vendor_id) ? $p->category_vendor_id : [];

        $explicitSlugs = collect($catIds)
            ->map(fn ($id) => $activeCategoryIdToSlug->get((int) $id))
            ->filter()->values()->all();

        $addedTo = [];
        if (!empty($explicitSlugs)) {
            foreach ($explicitSlugs as $slug) {
                if (!$slug || !in_array($slug, $activeCategorySlugs, true)) continue;
                if (in_array($slug, $addedTo, true)) continue;
                $homePackagesByCategoryExplicit[$slug] = ($homePackagesByCategoryExplicit[$slug] ?? collect())->push($p);
                $addedTo[] = $slug;
            }
        } else {
            $vendorSlugs = is_array($p->vendor->categories) && count($p->vendor->categories)
                ? $p->vendor->categories
                : ($p->vendor->category ? [$p->vendor->category] : []);
            foreach ($vendorSlugs as $slug) {
                if (!$slug || !in_array($slug, $activeCategorySlugs, true)) continue;
                if (in_array($slug, $addedTo, true)) continue;
                $homePackagesByCategoryFallback[$slug] = ($homePackagesByCategoryFallback[$slug] ?? collect())->push($p);
                $addedTo[] = $slug;
            }
        }
    }

    // Merge: explicit first, then fallback (deduped by package id)
    $homePackagesByCategory = collect();
    foreach ($activeCategorySlugs as $slug) {
        $explicit = $homePackagesByCategoryExplicit[$slug] ?? collect();
        $fallback = $homePackagesByCategoryFallback[$slug] ?? collect();
        $merged = $explicit->merge($fallback->reject(fn ($p) => $explicit->contains('id', $p->id)));
        if ($merged->isNotEmpty()) {
            $homePackagesByCategory[$slug] = $merged;
        }
    }

    $homePackagesByCity = collect();
    foreach ($homePackages as $p) {
        if (!$p->vendor || !$p->vendor->city) continue;
        $city = $p->vendor->city;
        $homePackagesByCity[$city] = ($homePackagesByCity[$city] ?? collect())->push($p);
    }

    $homePromoPackages = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->where('discount', '>', 0)
        ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
        ->with([
            'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'city', 'cover_image', 'logo_vendor'),
        ])
        ->orderByDesc('discount')
        ->limit(12)
        ->get();

    $venueReviewVideos = \App\Models\VenueReviewVideo::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $realWeddings = \App\Models\RealWedding::where('is_active', true)
        ->orderBy('sort_order')
        ->limit(10)
        ->get();

    $homeAd = \App\Models\HomeAd::where('is_active', true)
        ->where('type', 'card')
        ->orderBy('sort_order')
        ->first();

    $homeFeaturedBlogs = \App\Models\Blog::published()
        ->orderBy('published_at', 'desc')
        ->limit(2)
        ->get();

    $homePopularBlogs = \App\Models\Blog::published()
        ->orderBy('views_count', 'desc')
        ->limit(3)
        ->get();

    return view('front.home', compact('heroCircles', 'homeCategories', 'homePackagesByCategory', 'homePackagesByCity', 'homePromoPackages', 'venueReviewVideos', 'realWeddings', 'homeAd', 'homeFeaturedBlogs', 'homePopularBlogs'));
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
    $search  = trim(request('q', ''));
    $kategori = trim(request('kategori', ''));

    $categories = \App\Models\CategoryVendor::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $activeCategoryIdToSlug = $categories->pluck('slug', 'id');
    $activeCategorySlugs    = $categories->pluck('slug')->filter()->all();
    $kategoriCat = $kategori !== '' ? $categories->firstWhere('slug', $kategori) : null;

    $query = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
        ->with([
            'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'price_start', 'discount', 'cover_image'),
        ])
        ->orderBy('sort_order')
        ->orderBy('price');

    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhereHas('vendor', fn ($vq) => $vq->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%"));
        });
    }

    if ($kategoriCat) {
        $catId = $kategoriCat->id;
        $query->where(function ($q) use ($kategori, $catId) {
            // package-level explicit category
            $q->whereJsonContains('category_vendor_id', (string) $catId)
              ->orWhereJsonContains('category_vendor_id', (int) $catId)
              // fallback: vendor-level category
              ->orWhereHas('vendor', fn ($vq) => $vq->where('category', $kategori)
                  ->orWhereJsonContains('categories', $kategori));
        });
    }

    $packages = $query->get();

    // Two-bucket grouping: explicit package category first, then vendor category fallback
    $explicitBucket  = collect();
    $fallbackBucket  = collect();

    foreach ($packages as $p) {
        if (!$p->vendor) continue;
        $pkgCatIds = is_array($p->category_vendor_id) ? $p->category_vendor_id : [];
        $explicitSlugs = collect($pkgCatIds)
            ->map(fn ($id) => $activeCategoryIdToSlug->get((int) $id))
            ->filter()->values()->all();

        $addedTo = [];
        if (!empty($explicitSlugs)) {
            foreach ($explicitSlugs as $slug) {
                if (!$slug || !in_array($slug, $activeCategorySlugs, true)) continue;
                if (in_array($slug, $addedTo, true)) continue;
                $explicitBucket[$slug] = ($explicitBucket[$slug] ?? collect())->push($p);
                $addedTo[] = $slug;
            }
        } else {
            $vendorSlugs = is_array($p->vendor->categories) && count($p->vendor->categories)
                ? $p->vendor->categories
                : ($p->vendor->category ? [$p->vendor->category] : []);
            foreach ($vendorSlugs as $slug) {
                if (!$slug || !in_array($slug, $activeCategorySlugs, true)) continue;
                if (in_array($slug, $addedTo, true)) continue;
                $fallbackBucket[$slug] = ($fallbackBucket[$slug] ?? collect())->push($p);
                $addedTo[] = $slug;
            }
        }
    }

    $packagesByCategory = collect();
    foreach ($activeCategorySlugs as $slug) {
        $explicit = $explicitBucket[$slug] ?? collect();
        $fallback = $fallbackBucket[$slug] ?? collect();
        $merged   = $explicit->merge($fallback->reject(fn ($p) => $explicit->contains('id', $p->id)));
        if ($merged->isNotEmpty()) {
            $packagesByCategory[$slug] = $merged;
        }
    }

    $uncategorizedPackages = $packages->filter(function ($p) use ($activeCategorySlugs, $activeCategoryIdToSlug) {
        if (!$p->vendor) return true;
        $pkgCatIds = is_array($p->category_vendor_id) ? $p->category_vendor_id : [];
        foreach ($pkgCatIds as $id) {
            $slug = $activeCategoryIdToSlug->get((int) $id);
            if ($slug && in_array($slug, $activeCategorySlugs, true)) return false;
        }
        $vendorSlugs = is_array($p->vendor->categories) && count($p->vendor->categories)
            ? $p->vendor->categories
            : ($p->vendor->category ? [$p->vendor->category] : []);
        foreach ($vendorSlugs as $slug) {
            if ($slug && in_array($slug, $activeCategorySlugs, true)) return false;
        }
        return true;
    });

    return view('front.store', compact('categories', 'packagesByCategory', 'uncategorizedPackages', 'search', 'kategori', 'kategoriCat'));
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
        )
        ->where(fn ($q) => $q
            ->whereJsonContains('category_vendor_id', (string) $category->id)
            ->orWhereHas('vendor', fn ($vq) => $vq
                ->where(fn ($w) => $w
                    ->where('category', $category->slug)
                    ->orWhereJsonContains('categories', $category->slug)
                )
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
        'termurah' => $packagesQuery->orderBy('price')->orderBy('sort_order'),
        'termahal' => $packagesQuery->orderByDesc('price')->orderBy('sort_order'),
        'terbaru' => $packagesQuery->orderByDesc('created_at')->orderBy('sort_order'),
        default => $packagesQuery->orderBy('sort_order')->orderBy('price'),
    };

    $packages = $packagesQuery->paginate(24)->withQueryString();

    return view('store.store-category', compact('category', 'packages', 'sort', 'q'));
})->name('store.category');

Route::get('/store/kota/{city}', function (string $city) {
    $sort = request()->query('sort', 'rekomendasi');
    $allowedSort = ['rekomendasi', 'termurah', 'termahal', 'terbaru'];
    if (!in_array($sort, $allowedSort, true)) {
        $sort = 'rekomendasi';
    }

    $q = trim((string) request()->query('q', ''));

    $packagesQuery = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($vq) => $vq
            ->where('is_active', true)
            ->where('is_profile_complete', true)
            ->where('city', $city)
        )
        ->with([
            'vendor' => fn ($vq) => $vq->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'cover_image'),
        ]);

    if ($q !== '') {
        $packagesQuery->where(function ($qq) use ($q) {
            $qq->where('name', 'like', '%' . $q . '%')
                ->orWhereHas('vendor', function ($vq) use ($q) {
                    $vq->where('name', 'like', '%' . $q . '%')
                        ->orWhere('location', 'like', '%' . $q . '%');
                });
        });
    }

    $packagesQuery = match ($sort) {
        'termurah' => $packagesQuery->orderBy('price')->orderBy('sort_order'),
        'termahal' => $packagesQuery->orderByDesc('price')->orderBy('sort_order'),
        'terbaru' => $packagesQuery->orderByDesc('created_at')->orderBy('sort_order'),
        default => $packagesQuery->orderBy('sort_order')->orderBy('price'),
    };

    $packages = $packagesQuery->paginate(24)->withQueryString();

    return view('store.store-city', compact('city', 'packages', 'sort', 'q'));
})->name('store.city');

Route::get('/store/promo', function () {
    $sort = request()->query('sort', 'diskon');
    $allowedSort = ['diskon', 'termurah', 'termahal', 'terbaru'];
    if (!in_array($sort, $allowedSort, true)) {
        $sort = 'diskon';
    }

    $q = trim((string) request()->query('q', ''));

    $packagesQuery = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->where('discount', '>', 0)
        ->whereHas('vendor', fn ($vq) => $vq
            ->where('is_active', true)
            ->where('is_profile_complete', true)
        )
        ->with([
            'vendor' => fn ($vq) => $vq->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'cover_image', 'logo_vendor'),
        ]);

    if ($q !== '') {
        $packagesQuery->where(function ($qq) use ($q) {
            $qq->where('name', 'like', '%' . $q . '%')
                ->orWhereHas('vendor', function ($vq) use ($q) {
                    $vq->where('name', 'like', '%' . $q . '%')
                        ->orWhere('city', 'like', '%' . $q . '%');
                });
        });
    }

    $packagesQuery = match ($sort) {
        'termurah' => $packagesQuery->orderBy('price')->orderBy('sort_order'),
        'termahal' => $packagesQuery->orderByDesc('price')->orderBy('sort_order'),
        'terbaru' => $packagesQuery->orderByDesc('created_at')->orderBy('sort_order'),
        default => $packagesQuery->orderByDesc('discount')->orderBy('sort_order'),
    };

    $packages = $packagesQuery->paginate(24)->withQueryString();

    return view('store.store-promo', compact('packages', 'sort', 'q'));
})->name('store.promo');

Route::get('/blog', function () {
    $q        = request('q');
    $category = request('category');
    $sort     = request('sort', 'terbaru');

    $query = \App\Models\Blog::published();

    if ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%");
        });
    }

    if ($category) {
        $query->where('category', $category);
    }

    $query = match ($sort) {
        'populer' => $query->orderByDesc('views_count'),
        default   => $query->orderByDesc('published_at'),
    };

    $blogs = $query->paginate(12)->withQueryString();

    $categories = \App\Models\Blog::published()
        ->select('category')
        ->distinct()
        ->whereNotNull('category')
        ->pluck('category');

    $popularBlogs = \App\Models\Blog::published()
        ->orderByDesc('views_count')
        ->limit(5)
        ->get();

    return view('blog.index', compact('blogs', 'categories', 'popularBlogs', 'q', 'sort', 'category'));
})->name('blog.index');

Route::get('/search', function () {
    $q = trim(request('q', ''));

    $vendors = collect();
    $packages = collect();

    if ($q !== '') {
        $vendors = \App\Models\Vendor::where('is_active', true)
            ->where('is_profile_complete', true)
            ->where(fn ($w) =>
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('location', 'like', "%{$q}%")
                  ->orWhere('city', 'like', "%{$q}%")
            )
            ->withCount('galleries')
            ->withCount(['approvedReviews as comments_count', 'likedByUsers as likes'])
            ->withAvg('approvedReviews as rating', 'rating')
            ->with(['galleries' => fn ($gq) => $gq->where('is_cover', true), 'cheapestPackage'])
            ->orderByDesc('rating')
            ->limit(20)
            ->get();

        $packages = \App\Models\VendorPackage::query()
            ->where('is_active', true)
            ->whereHas('vendor', fn ($vq) => $vq->where('is_active', true)->where('is_profile_complete', true))
            ->where(fn ($w) =>
                $w->where('name', 'like', "%{$q}%")
                  ->orWhereHas('vendor', fn ($vq) =>
                      $vq->where('name', 'like', "%{$q}%")
                         ->orWhere('city', 'like', "%{$q}%")
                         ->orWhere('location', 'like', "%{$q}%")
                  )
            )
            ->with([
                'vendor' => fn ($vq) => $vq->select('id', 'name', 'slug', 'city', 'location', 'cover_image'),
            ])
            ->orderBy('price')
            ->limit(20)
            ->get();
    }

    return view('front.search', compact('q', 'vendors', 'packages'));
})->name('search');

Route::get('/review-videos', function () {
    $videos = \App\Models\VenueReviewVideo::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $packages = \App\Models\VendorPackage::query()
        ->where('is_active', true)
        ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
        ->with(['vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'city', 'location', 'cover_image')])
        ->inRandomOrder()
        ->limit(6)
        ->get();

    return view('front.review-videos', compact('videos', 'packages'));
})->name('review-videos');

Route::get('/tentang', function () {
    return view('front.tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('front.kontak');
})->name('kontak');

Route::post('/kontak', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama'   => ['required', 'string', 'max:100'],
        'email'  => ['required', 'email', 'max:150'],
        'subjek' => ['required', 'string', 'max:150'],
        'pesan'  => ['required', 'string', 'max:2000'],
    ]);

    \Illuminate\Support\Facades\Mail::send(
        'emails.kontak',
        ['nama' => $request->nama, 'email' => $request->email, 'subjek' => $request->subjek, 'pesan' => $request->pesan],
        function ($m) use ($request) {
            $m->to(config('mail.from.address', 'office@makruwedding.idn'))
              ->replyTo($request->email, $request->nama)
              ->subject('[Kontak] ' . $request->subjek);
        }
    );

    return redirect()->route('kontak')->with('kontak_success', 'Pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.');
})->name('kontak.send');

Route::get('/blog/{blog:slug}', function (\App\Models\Blog $blog) {
    abort_unless($blog->is_published, 404);

    $blog->increment('views_count');

    $related = \App\Models\Blog::published()
        ->where('id', '!=', $blog->id)
        ->when($blog->category, fn ($q) => $q->where('category', $blog->category))
        ->orderByDesc('published_at')
        ->limit(3)
        ->get();

    $popularBlogs = \App\Models\Blog::published()
        ->where('id', '!=', $blog->id)
        ->orderByDesc('views_count')
        ->limit(5)
        ->get();

    return view('blog.show', compact('blog', 'related', 'popularBlogs'));
})->name('blog.show');

Route::get('/real-wedding', function () {
    $q    = request('q');
    $sort = request('sort', 'terbaru');

    $query = \App\Models\RealWedding::where('is_active', true);

    if ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('couple_names', 'like', "%{$q}%")
                ->orWhere('venue_name', 'like', "%{$q}%");
        });
    }

    $query = match ($sort) {
        'terlama'   => $query->orderBy('wedding_date'),
        'az'        => $query->orderBy('couple_names'),
        default     => $query->orderByDesc('wedding_date'),
    };

    $realWeddings = $query->paginate(20)->withQueryString();

    return view('real-wedding.index', compact('realWeddings', 'sort', 'q'));
})->name('real-wedding.index');

Route::get('/real-wedding/{realWedding:slug}', function (\App\Models\RealWedding $realWedding) {
    abort_unless($realWedding->is_active, 404);

    $realWedding->increment('views_count');

    $realWedding->load(['vendors', 'vendorPackages.vendor']);

    $related = \App\Models\RealWedding::where('is_active', true)
        ->where('id', '!=', $realWedding->id)
        ->orderByDesc('wedding_date')
        ->limit(5)
        ->get();

    return view('real-wedding.show', compact('realWedding', 'related'));
})->name('real-wedding.show');

Route::get('/store/paket/{package}', function (\App\Models\VendorPackage $package) {
    abort_unless($package->is_active, 404);
    $package->load([
        'vendor' => fn ($q) => $q->with([
            'galleries' => fn ($g) => $g->orderBy('sort_order'),
        ]),
    ]);

    $vendor = $package->vendor;
    abort_unless($vendor && $vendor->is_active && $vendor->is_profile_complete, 404);

    $images = collect(is_array($package->image_path ?? null) ? $package->image_path : [$package->image_path])
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

    if ($images->isEmpty()) {
        $images = collect($vendor->galleries)
            ->pluck('image_url')
            ->filter()
            ->values();
    }

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
        ->orderBy('price')
        ->get();

    $videos = $package->galleries()
        ->orderBy('id')
        ->get();

    return view('store.store-detail', compact('package', 'vendor', 'images', 'otherPackages', 'videos'));
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
              ->orWhere('location', 'like', "%{$q}%")
              ->orWhere('city', 'like', "%{$q}%")
              ->orWhereHas('packages', fn ($p) =>
                  $p->where('is_active', true)
                    ->where('name', 'like', "%{$q}%")
              )
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
                fn ($u) => $u->whereHas('roles', fn ($r) => $r->where('name', 'pengunjung')->where('guard_name', 'web'))
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
    $packages = $vendor->packages->where('is_active', true)->values();
    return view('vendor.detail', compact('vendor', 'hasLiked', 'myBooking', 'vendorDetailDisabled', 'vendorDetailBackUrl', 'packages'));
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
    $bookingOwnerBlocked = $authUser && (int) $vendor->owner_user_id === (int) $authUser->id;
    abort_if(!$vendor->is_active && !($isPrivileged || $isVendorOwner), 404);

    $vendorBookingDisabled = !$vendor->is_profile_complete && !$isPrivileged;
    $vendorBookingBackUrl = $isVendorOwner || $isPrivileged ? route('vendor.edit', $vendor) : route('vendor.detail', $vendor);

    $packagesQuery = $vendor->packages()->orderBy('sort_order')->orderBy('price');
    if (!($isPrivileged || $isVendorOwner)) {
        $packagesQuery->where('is_active', true);
    }
    $packages = $packagesQuery->get();

    $selectedPackageId = (int) request()->query('vendor_package_id', 0);
    $selectedPackage = $selectedPackageId ? $packages->firstWhere('id', $selectedPackageId) : null;

    return view('booking.create', compact('vendor', 'packages', 'selectedPackage', 'vendorBookingDisabled', 'vendorBookingBackUrl', 'bookingOwnerBlocked'));
})->name('booking.vendor');

Route::get('/booking/paket/{package}', function (\App\Models\VendorPackage $package) {
    abort_unless($package->is_active, 404);

    $qty = max(1, min(99, (int) request()->query('qty', 1)));
    $package->load([
        'vendor' => fn ($q) => $q->select('id', 'owner_user_id', 'name', 'slug', 'category', 'categories', 'city', 'is_active', 'is_profile_complete'),
    ]);

    $vendor = $package->vendor;
    abort_unless($vendor && $vendor->is_active, 404);

    $authUser = Auth::check() ? \App\Models\User::find(Auth::id()) : null;
    $isPrivileged = $authUser?->hasRole(['super_admin', 'admin']) ?? false;
    $isVendorOwner = $authUser?->hasRole(['vendor']) && (int) $vendor->owner_user_id === (int) $authUser->id;
    $bookingOwnerBlocked = $authUser && (int) $vendor->owner_user_id === (int) $authUser->id;

    $vendorBookingDisabled = !$vendor->is_profile_complete && !$isPrivileged;
    $vendorBookingBackUrl = $isVendorOwner || $isPrivileged ? route('vendor.edit', $vendor) : route('store.package.show', $package);

    $packagesQuery = $vendor->packages()->orderBy('sort_order')->orderBy('price');
    if (!($isPrivileged || $isVendorOwner)) {
        $packagesQuery->where('is_active', true);
    }
    $packages = $packagesQuery->get();

    $selectedPackage = $packages->firstWhere('id', $package->id);

    return view('booking.create', compact('vendor', 'packages', 'selectedPackage', 'vendorBookingDisabled', 'vendorBookingBackUrl', 'bookingOwnerBlocked'))
        ->with('qty', $qty);
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

Route::post('/dashboard/ulasan/{review}/approve', [VendorReviewModerationController::class, 'approve'])
    ->name('dashboard.ulasan.approve')
    ->middleware(['auth', 'verified']);

Route::post('/dashboard/ulasan/{review}/reject', [VendorReviewModerationController::class, 'reject'])
    ->name('dashboard.ulasan.reject')
    ->middleware(['auth', 'verified']);

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

Route::post('/dashboard/theme', [ThemeController::class, 'update'])
    ->name('dashboard.theme.update')
    ->middleware(['auth', 'verified', 'role:super_admin']);

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

    $agreedTotal = $booking->vendorPackage ? (int) ($booking->vendorPackage->price ?? 0) : null;

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

Route::get('/dashboard/paket', function () {
    $user = \App\Models\User::findOrFail(Auth::id());

    $isAdmin = $user->hasRole(['super_admin', 'admin']);
    $vendors = \App\Models\Vendor::where('owner_user_id', $user->id)->get(['id', 'name', 'slug']);
    $vendorIds = $vendors->pluck('id');
    $isPemilikPaket = $vendorIds->isNotEmpty();

    abort_unless($isAdmin || $isPemilikPaket, 403);

    $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
    $favoriteCount = $user->likedVendors()->count();
    $bookingCount = $user->vendorBookings()->count();
    $bookingUserCount = $isAdmin ? \App\Models\VendorBooking::where('status', 'pending')->count() : 0;

    $q = trim((string) request('q', ''));
    $statusFilter = request('status', '');

    $query = \App\Models\VendorPackage::query()
        ->with(['vendor:id,name,slug,owner_user_id'])
        ->orderBy('vendor_id')
        ->orderBy('sort_order')
        ->orderBy('price');

    if (!$isAdmin) {
        $query->whereIn('vendor_id', $vendorIds);
    }

    if ($q !== '') {
        $query->where(function ($sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhereHas('vendor', fn ($vq) => $vq->where('name', 'like', "%{$q}%"));
        });
    }

    if ($statusFilter === 'aktif') {
        $query->where('is_active', true);
    } elseif ($statusFilter === 'nonaktif') {
        $query->where('is_active', false);
    }

    $packages = $query->paginate(30)->withQueryString();

    return view('dashboard.paket', compact(
        'user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount',
        'packages', 'q', 'statusFilter', 'isAdmin', 'vendors'
    ));
})->name('dashboard.paket')->middleware(['auth', 'verified']);

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

// ── Chat (public) ──────────────────────────────────────────────────────────
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start')
    ->middleware('throttle:20,1');
Route::post('/chat/{token}/send', [ChatController::class, 'send'])->name('chat.send')
    ->middleware('throttle:60,1');
Route::get('/chat/{token}/messages', [ChatController::class, 'poll'])->name('chat.poll')
    ->middleware('throttle:120,1');

// ── Chat (admin) ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('dashboard/chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'adminIndex'])->name('admin');
    Route::get('/notify', [ChatController::class, 'adminNotify'])->name('admin.notify');
    Route::get('/{token}', [ChatController::class, 'adminDetail'])->name('admin.detail');
    Route::post('/{token}/reply', [ChatController::class, 'adminReply'])->name('admin.reply');
    Route::get('/{token}/poll', [ChatController::class, 'adminPoll'])->name('admin.poll');
    Route::delete('/{token}/messages/{message}', [ChatController::class, 'adminDeleteMessage'])->name('admin.message.delete');
    Route::delete('/{token}', [ChatController::class, 'adminDeleteSession'])->name('admin.delete');
    Route::post('/{token}/close', [ChatController::class, 'adminClose'])->name('admin.close');
    Route::post('/{token}/open', [ChatController::class, 'adminOpen'])->name('admin.open');
});
