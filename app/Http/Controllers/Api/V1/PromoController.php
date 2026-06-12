<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PromoService;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct(private PromoService $promoService) {}

    /** Validasi kode promo dan kembalikan nominal diskon. */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code'              => ['required', 'string', 'max:50'],
            'subtotal'          => ['required', 'integer', 'min:0'],
            'vendor_package_id' => ['nullable', 'integer'],
        ]);

        $result = $this->promoService->validate(
            $request->input('code'),
            (int) $request->input('subtotal'),
            $request->input('vendor_package_id') ? (int) $request->input('vendor_package_id') : null,
            $request->user()?->id,
        );

        return response()->json([
            'valid'    => $result['valid'],
            'discount' => $result['discount'],
            'message'  => $result['message'],
        ]);
    }
}
