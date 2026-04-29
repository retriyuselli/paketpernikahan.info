<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorBookingController extends Controller
{
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
        ]);

        $normalizedPhone = VendorBooking::normalizeWhatsappNumber($data['phone']);
        if (strlen($normalizedPhone) < 10 || strlen($normalizedPhone) > 15 || !str_starts_with($normalizedPhone, '62')) {
            return back()
                ->withErrors(['phone' => 'Nomor WhatsApp tidak valid.'], 'booking')
                ->withInput();
        }

        $qty = max(1, min(99, (int) ($data['qty'] ?? 1)));
        $package = isset($data['vendor_package_id'])
            ? $vendor->packages()
                ->select(['id', 'price', 'discount', 'dp_paket'])
                ->whereKey((int) $data['vendor_package_id'])
                ->first()
            : null;

        $agreedTotal = null;
        $dpRequiredAmount = null;
        if ($package) {
            $priceRaw = (int) ($package->price ?? 0);
            $discount = (int) ($package->discount ?? 0);
            $unitFinal = max($priceRaw - $discount, 0);
            $agreedTotal = $unitFinal * $qty;

            $dp = (int) ($package->dp_paket ?? 0);
            $dpRequiredAmount = $dp > 0 ? ($dp * $qty) : null;
        }

        $booking = VendorBooking::create([
            'vendor_id'         => $vendor->id,
            'user_id'           => $request->user()->id,
            'vendor_package_id' => $data['vendor_package_id'] ?? null,
            'agreed_total'      => $agreedTotal,
            'dp_required_amount'=> $dpRequiredAmount,
            'event_date'        => $data['event_date'],
            'phone'             => $normalizedPhone,
            'notes'             => $data['notes'] ?? null,
            'status'            => 'pending',
        ]);

        return back()
            ->with('booking_success', 'Booking berhasil dikirim. Tim kami akan segera menghubungi Anda.')
            ->with('booking_id', $booking->id);
    }
}
