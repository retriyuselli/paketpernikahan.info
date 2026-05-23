<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorBooking;
use App\Services\BookingService;
use App\Services\PromoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private PromoService $promoService,
    ) {}

    public function store(Request $request, Vendor $vendor)
    {
        if ((int) $vendor->owner_user_id === (int) $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak dapat melakukan booking pada vendor milik sendiri.',
                ], 403);
            }

            return back()->withErrors([
                'booking' => 'Anda tidak dapat melakukan booking pada vendor milik sendiri.',
            ], 'booking');
        }

        $hasPackages = $vendor->packages()->exists();

        $data = $request->validateWithBag('booking', [
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
            return back()
                ->withErrors(['phone' => 'Nomor WhatsApp tidak valid.'], 'booking')
                ->withInput();
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

                $promoResult = $this->promoService->validate($promoCode, $subtotal, isset($data['vendor_package_id']) ? (int) $data['vendor_package_id'] : null, $request->user()->id);
                if ($promoResult['valid']) {
                    $promo = $promoResult['promo'];
                }
            }
        }

        $booking = $this->bookingService->createBooking($vendor, $request->user(), $data, $promo);

        return back()
            ->with('booking_success', 'Booking berhasil dikirim. Tim kami akan segera menghubungi Anda.')
            ->with('booking_id', $booking->id);
    }
}
