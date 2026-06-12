<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Vendor;
use App\Models\VendorBooking;
use App\Services\BookingService;
use App\Services\PromoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private PromoService $promoService,
    ) {}

    /** Riwayat booking milik user yang sedang login. */
    public function index(Request $request)
    {
        $bookings = VendorBooking::where('user_id', $request->user()->id)
            ->with(['vendor', 'vendorPackage'])
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return BookingResource::collection($bookings);
    }

    /**
     * Buat booking baru. Logika sama dengan VendorBookingController
     * versi web — harga selalu dihitung server-side via BookingService.
     */
    public function store(Request $request, Vendor $vendor)
    {
        if ((int) $vendor->owner_user_id === (int) $request->user()->id) {
            return response()->json([
                'message' => 'Anda tidak dapat melakukan booking pada vendor milik sendiri.',
            ], 403);
        }

        $hasPackages = $vendor->packages()->exists();

        $data = $request->validate([
            'vendor_package_id' => [
                $hasPackages ? 'required' : 'nullable',
                'integer',
                Rule::exists('vendor_packages', 'id')->where('vendor_id', $vendor->id),
            ],
            'qty'        => ['nullable', 'integer', 'min:1', 'max:99'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'phone'      => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s.-]{8,30}$/'],
            'notes'      => ['nullable', 'string', 'max:2000'],
            'promo_code' => ['nullable', 'string', 'max:50'],
        ]);

        $normalizedPhone = VendorBooking::normalizeWhatsappNumber($data['phone']);
        if (strlen($normalizedPhone) < 10 || strlen($normalizedPhone) > 15 || !str_starts_with($normalizedPhone, '62')) {
            return response()->json([
                'message' => 'Nomor WhatsApp tidak valid.',
                'errors'  => ['phone' => ['Nomor WhatsApp tidak valid.']],
            ], 422);
        }

        $data['phone'] = $normalizedPhone;

        // Resolve promo jika ada kode — validasi server-side ulang
        $promo = null;
        $promoCode = trim((string) ($data['promo_code'] ?? ''));
        if ($promoCode !== '') {
            $package = isset($data['vendor_package_id'])
                ? $vendor->packages()
                    ->select(['id', 'price', 'discount'])
                    ->whereKey((int) $data['vendor_package_id'])
                    ->first()
                : null;

            if ($package) {
                $qty      = max(1, min(99, (int) ($data['qty'] ?? 1)));
                $subtotal = max((int) $package->price - (int) ($package->discount ?? 0), 0) * $qty;

                $promoResult = $this->promoService->validate($promoCode, $subtotal, (int) $data['vendor_package_id'], $request->user()->id);
                if ($promoResult['valid']) {
                    $promo = $promoResult['promo'];
                }
            }
        }

        $booking = $this->bookingService->createBooking($vendor, $request->user(), $data, $promo);
        $booking->load(['vendor', 'vendorPackage']);

        return (new BookingResource($booking))
            ->additional(['message' => 'Booking berhasil dikirim. Tim kami akan segera menghubungi Anda.'])
            ->response()
            ->setStatusCode(201);
    }
}
