<?php

namespace App\Http\Controllers;

use App\Models\VendorReview;
use Illuminate\Http\Request;

class VendorReviewModerationController extends Controller
{
    public function approve(Request $request, VendorReview $review)
    {
        $user = $request->user();
        abort_unless($user && $user->hasRole(['super_admin', 'admin']), 403);

        $review->update([
            'is_approved' => true,
        ]);

        return back()->with('review_success', 'Ulasan berhasil disetujui.');
    }

    public function reject(Request $request, VendorReview $review)
    {
        $user = $request->user();
        abort_unless($user && $user->hasRole(['super_admin', 'admin']), 403);

        $review->update([
            'is_approved' => false,
        ]);

        return back()->with('review_success', 'Ulasan ditandai sebagai tidak disetujui.');
    }
}

