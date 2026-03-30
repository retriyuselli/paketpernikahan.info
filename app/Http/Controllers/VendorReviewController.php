<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorReviewController extends Controller
{
    public function store(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'body'          => 'required|string|min:10|max:1000',
        ]);

        $recentQuery = VendorReview::where('vendor_id', $vendor->id)
            ->where('created_at', '>=', now()->subHours(24));

        if ($request->user()) {
            $recentQuery->where('user_id', $request->user()->id);
        } else {
            $recentQuery->where('reviewer_ip', $request->ip());
        }

        $recent = $recentQuery->exists();

        if ($recent) {
            return response()->json([
                'message' => 'Anda sudah mengirimkan ulasan dalam 24 jam terakhir.',
            ], 429);
        }

        $user = $request->user();
        if ($user && (int) $vendor->owner_user_id === (int) $user->id) {
            return response()->json([
                'message' => 'Anda tidak dapat memberi ulasan pada vendor milik sendiri.',
            ], 403);
        }

        VendorReview::create([
            'vendor_id'     => $vendor->id,
            'user_id'       => $user?->id,
            'reviewer_name' => Str::limit((string) ($user?->name ?? 'Pengguna'), 100, ''),
            'reviewer_avatar' => $user?->avatarUrl(),
            'rating'        => $data['rating'],
            'body'          => $data['body'],
            'reviewed_at'   => now(),
            'is_approved'   => false,
            'reviewer_ip'   => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Terima kasih! Ulasan Anda sedang menunggu persetujuan admin.',
        ], 201);
    }
}
