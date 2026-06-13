<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BlogResource;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\PackageResource;
use App\Http\Resources\Api\V1\RealWeddingResource;
use App\Models\Blog;
use App\Models\CategoryVendor;
use App\Models\HomeAd;
use App\Models\PartnerLogo;
use App\Models\RealWedding;
use App\Models\VendorPackage;
use App\Models\VenueReviewVideo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Data gabungan untuk layar utama aplikasi, meniru susunan
     * seksi halaman web beranda (front/home.blade.php).
     */
    public function index()
    {
        $categories = CategoryVendor::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Dasar pengelompokan kategori & kota — query sama dengan route web '/'
        $homePackages = VendorPackage::query()
            ->where('is_active', true)
            ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
            ->with('vendor')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        [$packageSections, $citySections] = $this->groupPackages($homePackages, $categories);

        $promoPackages = VendorPackage::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q
                ->where('discount', '>', 0)
                ->orWhereHas('promos', fn ($q) => $q->active()->available())
            )
            ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
            ->with('vendor')
            ->orderByDesc('discount')
            ->limit(12)
            ->get();

        $reviewVideos = VenueReviewVideo::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($video) => [
                'id'            => $video->id,
                'title'         => $video->title,
                'subtitle'      => $video->subtitle,
                'location'      => $video->location,
                'thumbnail_url' => $video->thumbnail
                    ? (str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : url(Storage::url($video->thumbnail)))
                    : null,
                'video_url'     => $video->video_url,
            ]);

        // Logo partner — query sama dengan blok @php di home.blade.php
        $partnerLogos = PartnerLogo::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(12)
            ->get(['name', 'logo'])
            ->map(fn ($logo) => [
                'name'     => $logo->name ?: 'Partner',
                'logo_url' => str_starts_with($logo->logo, 'http')
                    ? $logo->logo
                    : url(Storage::url($logo->logo)),
            ]);

        $realWeddings = RealWedding::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        $homeAd = HomeAd::where('is_active', true)
            ->where('type', 'card')
            ->orderBy('sort_order')
            ->first();

        $featuredBlogs = Blog::published()->orderByDesc('published_at')->limit(2)->get();
        $popularBlogs  = Blog::published()->orderByDesc('views_count')->limit(3)->get();

        return response()->json([
            'data' => [
                'categories'       => CategoryResource::collection($categories),
                'partner_logos'    => $partnerLogos,
                'package_sections' => $packageSections,
                'promo_packages'   => PackageResource::collection($promoPackages),
                'city_sections'    => $citySections,
                'review_videos'    => $reviewVideos,
                'real_weddings'    => RealWeddingResource::collection($realWeddings),
                'home_ad'          => $homeAd ? [
                    'title'      => $homeAd->title,
                    'image_url'  => $homeAd->image ? url(Storage::url($homeAd->image)) : null,
                    'caption'    => $homeAd->caption,
                    'link_url'   => $homeAd->link_url,
                    'link_label' => $homeAd->link_label,
                ] : null,
                'featured_blogs'   => BlogResource::collection($featuredBlogs),
                'popular_blogs'    => BlogResource::collection($popularBlogs),
            ],
        ]);
    }

    /**
     * Kelompokkan paket per kategori (eksplisit dulu, lalu warisan dari
     * vendor) dan per kota — logika sama dengan route web '/', tiap
     * kelompok dibatasi 7 paket seperti tampilan blade.
     */
    private function groupPackages(Collection $packages, Collection $categories): array
    {
        $activeSlugs = $categories->pluck('slug')->filter()->all();
        $idToSlug    = $categories->pluck('slug', 'id');
        $nameBySlug  = $categories->pluck('name', 'slug');

        $explicit = [];
        $fallback = [];
        $byCity   = [];

        foreach ($packages as $package) {
            if (!$package->vendor) {
                continue;
            }

            $catIds = is_array($package->category_vendor_id) ? $package->category_vendor_id : [];
            $explicitSlugs = collect($catIds)
                ->map(fn ($id) => $idToSlug->get((int) $id))
                ->filter()
                ->unique()
                ->all();

            if (!empty($explicitSlugs)) {
                foreach ($explicitSlugs as $slug) {
                    if (in_array($slug, $activeSlugs, true)) {
                        $explicit[$slug][] = $package;
                    }
                }
            } else {
                $vendorSlugs = is_array($package->vendor->categories) && count($package->vendor->categories)
                    ? $package->vendor->categories
                    : ($package->vendor->category ? [$package->vendor->category] : []);

                foreach (array_unique($vendorSlugs) as $slug) {
                    if ($slug && in_array($slug, $activeSlugs, true)) {
                        $fallback[$slug][] = $package;
                    }
                }
            }

            if ($package->vendor->city) {
                $byCity[$package->vendor->city][] = $package;
            }
        }

        $packageSections = [];
        foreach ($activeSlugs as $slug) {
            $merged = collect($explicit[$slug] ?? []);
            $merged = $merged->merge(
                collect($fallback[$slug] ?? [])->reject(fn ($p) => $merged->contains('id', $p->id))
            );

            if ($merged->isNotEmpty()) {
                $packageSections[] = [
                    'slug'     => $slug,
                    'name'     => $nameBySlug->get($slug),
                    'packages' => PackageResource::collection($merged->take(7)->values()),
                ];
            }
        }

        $citySections = [];
        foreach ($byCity as $city => $cityPackages) {
            $citySections[] = [
                'city'     => $city,
                'packages' => PackageResource::collection(collect($cityPackages)->take(7)->values()),
            ];
        }

        return [$packageSections, $citySections];
    }
}
