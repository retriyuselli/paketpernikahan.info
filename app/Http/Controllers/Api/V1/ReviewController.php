<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function store(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body'   => 'required|string|min:10|max:1000',
            'photo'  => 'nullable|image|max:5120',
        ]);

        $user = $request->user();

        $recent = VendorReview::where('vendor_id', $vendor->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->where('user_id', $user->id)
            ->exists();

        if ($recent) {
            return response()->json([
                'message' => 'Anda sudah mengirimkan ulasan dalam 24 jam terakhir.',
            ], 429);
        }

        if ((int) $vendor->owner_user_id === (int) $user->id) {
            return response()->json([
                'message' => 'Anda tidak dapat memberi ulasan pada vendor milik sendiri.',
            ], 403);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reviews', 'public');
        }

        VendorReview::create([
            'vendor_id'       => $vendor->id,
            'user_id'         => $user->id,
            'reviewer_name'   => Str::limit((string) $user->name, 100, ''),
            'reviewer_avatar' => $user->avatarUrl(),
            'rating'          => $data['rating'],
            'body'            => $data['body'],
            'photo'           => $photoPath,
            'reviewed_at'     => now(),
            'is_approved'     => false,
            'reviewer_ip'     => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Terima kasih! Ulasan Anda sedang menunggu persetujuan admin.',
        ], 201);
    }
}
