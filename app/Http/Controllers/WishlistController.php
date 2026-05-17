<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\VendorPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(VendorPackage $package)
    {
        $userId = Auth::id();

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

    public function index()
    {
        $packages = VendorPackage::whereHas('wishlists', fn($q) => $q->where('user_id', Auth::id()))
            ->with(['vendor', 'wishlists'])
            ->latest()
            ->paginate(20);

        return view('dashboard.wishlist', compact('packages'));
    }

    public function check(VendorPackage $package)
    {
        $wishlisted = Auth::check()
            ? Wishlist::where('user_id', Auth::id())
                ->where('vendor_package_id', $package->id)
                ->exists()
            : false;

        return response()->json(['wishlisted' => $wishlisted]);
    }
}
