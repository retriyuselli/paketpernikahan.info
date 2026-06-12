<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PackageResource;
use App\Models\VendorPackage;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $packages = VendorPackage::whereHas('wishlists', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with('vendor')
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return PackageResource::collection($packages);
    }

    public function toggle(Request $request, VendorPackage $package)
    {
        $userId = $request->user()->id;

        $existing = Wishlist::where('user_id', $userId)
            ->where('vendor_package_id', $package->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id'           => $userId,
                'vendor_package_id' => $package->id,
            ]);
            $wishlisted = true;
        }

        return response()->json([
            'wishlisted' => $wishlisted,
            'count'      => Wishlist::where('vendor_package_id', $package->id)->count(),
        ]);
    }
}
