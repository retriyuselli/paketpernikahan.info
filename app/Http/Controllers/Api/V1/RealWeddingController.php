<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PackageResource;
use App\Http\Resources\Api\V1\RealWeddingResource;
use App\Http\Resources\Api\V1\VendorSummaryResource;
use App\Models\RealWedding;
use Illuminate\Http\Request;

class RealWeddingController extends Controller
{
    public function index(Request $request)
    {
        $weddings = RealWedding::where('is_active', true)
            ->orderByDesc('wedding_date')
            ->paginate($request->integer('per_page', 15));

        return RealWeddingResource::collection($weddings);
    }

    /**
     * Detail real wedding untuk layar native — logika sama dengan
     * route web real-wedding.show (termasuk hitung views).
     */
    public function show(RealWedding $realWedding)
    {
        abort_unless($realWedding->is_active, 404);

        $realWedding->increment('views_count');

        $realWedding->load(['vendors', 'vendorPackages.vendor']);

        return response()->json([
            'data' => array_merge(
                (new RealWeddingResource($realWedding))->toArray(request()),
                [
                    'content'  => $realWedding->content,
                    'gallery'  => $realWedding->gallery_urls,
                    'vendors'  => VendorSummaryResource::collection($realWedding->vendors),
                    'packages' => PackageResource::collection($realWedding->vendorPackages),
                ]
            ),
        ]);
    }
}
