<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PackageDetailResource;
use App\Http\Resources\Api\V1\PackageResource;
use App\Models\CategoryVendor;
use App\Models\VendorPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Feed paket untuk layar Store, dengan filter pencarian dan kategori.
     * Logika query mengikuti halaman web /store.
     */
    public function index(Request $request)
    {
        $search   = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        $query = VendorPackage::query()
            ->where('is_active', true)
            ->whereHas('vendor', fn ($q) => $q->where('is_active', true)->where('is_profile_complete', true))
            ->with([
                'vendor' => fn ($q) => $q->select('id', 'name', 'slug', 'category', 'categories', 'city', 'location', 'price_start', 'discount', 'rating', 'cover_image'),
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

        if ($kategori !== '') {
            $kategoriCat = CategoryVendor::where('is_active', true)->where('slug', $kategori)->first();

            if ($kategoriCat) {
                $catId = $kategoriCat->id;
                $query->where(function ($q) use ($kategori, $catId) {
                    $q->whereJsonContains('category_vendor_id', (string) $catId)
                      ->orWhereJsonContains('category_vendor_id', (int) $catId)
                      ->orWhereHas('vendor', fn ($vq) => $vq->where('category', $kategori)
                          ->orWhereJsonContains('categories', $kategori));
                });
            }
        }

        return PackageResource::collection(
            $query->paginate($request->integer('per_page', 20))->appends($request->query())
        );
    }

    public function show(VendorPackage $package)
    {
        abort_unless($package->is_active, 404);

        $package->load(['vendor', 'galleries']);

        return new PackageDetailResource($package);
    }
}
