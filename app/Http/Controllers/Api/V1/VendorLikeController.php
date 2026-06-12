<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorLikeController extends Controller
{
    public function toggle(Request $request, Vendor $vendor)
    {
        $user = $request->user();

        $hasLiked = $user->likedVendors()->where('vendor_id', $vendor->id)->exists();

        if ($hasLiked) {
            $user->likedVendors()->detach($vendor->id);
        } else {
            $user->likedVendors()->attach($vendor->id);
        }

        return response()->json([
            'liked' => !$hasLiked,
            'count' => $vendor->likedByUsers()->count(),
        ]);
    }
}
