<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BlogResource;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\RealWeddingResource;
use App\Models\Blog;
use App\Models\CategoryVendor;
use App\Models\HomeAd;
use App\Models\RealWedding;

class HomeController extends Controller
{
    /**
     * Data gabungan untuk layar utama aplikasi, meniru data
     * yang disajikan halaman web /store.
     */
    public function index()
    {
        $categories = CategoryVendor::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

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
                'categories'     => CategoryResource::collection($categories),
                'real_weddings'  => RealWeddingResource::collection($realWeddings),
                'home_ad'        => $homeAd ? [
                    'title'      => $homeAd->title,
                    'image_url'  => $homeAd->image ? url(\Illuminate\Support\Facades\Storage::url($homeAd->image)) : null,
                    'caption'    => $homeAd->caption,
                    'link_url'   => $homeAd->link_url,
                    'link_label' => $homeAd->link_label,
                ] : null,
                'featured_blogs' => BlogResource::collection($featuredBlogs),
                'popular_blogs'  => BlogResource::collection($popularBlogs),
            ],
        ]);
    }
}
