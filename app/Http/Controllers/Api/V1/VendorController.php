<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\VendorDetailResource;
use App\Http\Resources\Api\V1\VendorSummaryResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $search   = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));
        $kota     = trim((string) $request->query('kota', ''));

        $query = Vendor::query()
            ->where('is_active', true)
            ->where('is_profile_complete', true)
            ->orderByDesc('rating');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($kategori !== '') {
            $query->where(function ($q) use ($kategori) {
                $q->where('category', $kategori)
                  ->orWhereJsonContains('categories', $kategori);
            });
        }

        if ($kota !== '') {
            $query->where('city', 'like', "%{$kota}%");
        }

        return VendorSummaryResource::collection(
            $query->paginate($request->integer('per_page', 20))->appends($request->query())
        );
    }

    public function show(Vendor $vendor)
    {
        abort_unless($vendor->is_active, 404);

        $vendor->load([
            'galleries',
            'packages' => fn ($q) => $q->where('is_active', true),
            'approvedReviews',
        ]);

        return new VendorDetailResource($vendor);
    }
}
