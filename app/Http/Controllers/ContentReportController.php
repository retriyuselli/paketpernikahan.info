<?php

namespace App\Http\Controllers;

use App\Models\ContentReport;
use App\Models\VendorReview;
use Illuminate\Http\Request;

class ContentReportController extends Controller
{
    public function reportReview(Request $request, VendorReview $review)
    {
        $request->validate([
            'reason' => ['required', 'string', 'in:spam,offensive,fake,other'],
        ]);

        $exists = ContentReport::where('reporter_id', $request->user()->id)
            ->where('reportable_type', VendorReview::class)
            ->where('reportable_id', $review->id)
            ->exists();

        if (!$exists) {
            ContentReport::create([
                'reporter_id'     => $request->user()->id,
                'reportable_type' => VendorReview::class,
                'reportable_id'   => $review->id,
                'reason'          => $request->reason,
            ]);
        }

        return back()->with('report_success', 'Laporan berhasil dikirim. Tim kami akan meninjau dalam 24 jam.');
    }
}
