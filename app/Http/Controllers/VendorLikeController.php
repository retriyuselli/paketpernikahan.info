<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorLikeController extends Controller
{
    /**
     * Toggle the like status for a vendor by the authenticated user.
     */
    public function toggle(Vendor $vendor)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk menyukai vendor.');
        }

        $hasLiked = $user->likedVendors()->where('vendor_id', $vendor->id)->exists();

        if ($hasLiked) {
            $user->likedVendors()->detach($vendor->id);
            $message = 'Batal menyukai vendor ini.';
        } else {
            $user->likedVendors()->attach($vendor->id);
            $message = 'Berhasil menyukai vendor ini!';
        }

        return back()->with('success', $message);
    }
}
