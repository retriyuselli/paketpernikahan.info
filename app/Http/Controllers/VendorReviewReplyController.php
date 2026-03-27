<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorReviewReplyController extends Controller
{
    public function store(Request $request, VendorReview $review)
    {
        $user = Auth::check() ? User::find(Auth::id()) : null;
        abort_unless($user && $user->hasRole(['super_admin', 'admin']), 403);

        $data = $request->validate([
            'admin_reply' => ['nullable', 'string', 'max:2000'],
        ]);

        $reply = trim((string) ($data['admin_reply'] ?? ''));

        if ($reply === '') {
            $review->update([
                'admin_reply' => null,
                'admin_reply_by' => null,
                'admin_replied_at' => null,
            ]);

            return back()->with('reply_success', 'Balasan dihapus.');
        }

        $review->update([
            'admin_reply' => $reply,
            'admin_reply_by' => $user->id,
            'admin_replied_at' => now(),
        ]);

        return back()->with('reply_success', 'Balasan berhasil disimpan.');
    }
}
