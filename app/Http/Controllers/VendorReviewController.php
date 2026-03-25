<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorReview;
use Illuminate\Http\Request;

class VendorReviewController extends Controller
{
    public function store(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'body'          => 'required|string|min:10|max:1000',
        ]);

        // Prevent duplicate: same IP + same vendor within 24h
        $recent = VendorReview::where('vendor_id', $vendor->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->where(function ($q) use ($request) {
                $q->where('reviewer_ip', $request->ip());
                if (auth()->check()) {
                    $q->orWhere('user_id', auth()->id());
                }
            })
            ->exists();

        if ($recent) {
            return response()->json([
                'message' => 'Anda sudah mengirimkan ulasan dalam 24 jam terakhir.',
            ], 429);
        }

        VendorReview::create([
            'vendor_id'     => $vendor->id,
            'user_id'       => auth()->id(),
            'reviewer_name' => $data['reviewer_name'],
            'reviewer_avatar' => auth()->check() && auth()->user()->avatar
                                    ? auth()->user()->avatar
                                    : null,
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
