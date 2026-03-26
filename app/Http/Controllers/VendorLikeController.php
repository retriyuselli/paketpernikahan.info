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

        // Jika user belum login, kita arahkan ke halaman login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk menyukai vendor.');
        }

        // Cek apakah user sudah melike vendor ini
        $hasLiked = $user->likedVendors()->where('vendor_id', $vendor->id)->exists();

        if ($hasLiked) {
            // Jika sudah like, maka lakukan unlike (detach)
            $user->likedVendors()->detach($vendor->id);
            // Kurangi angka likes di tabel vendors jika lebih dari 0
            if ($vendor->likes > 0) {
                $vendor->decrement('likes');
            }
            $message = 'Batal menyukai vendor ini.';
        } else {
            // Jika belum like, maka lakukan like (attach)
            $user->likedVendors()->attach($vendor->id);
            // Tambah angka likes di tabel vendors
            $vendor->increment('likes');
            $message = 'Berhasil menyukai vendor ini!';
        }

        return back()->with('success', $message);
    }
}
