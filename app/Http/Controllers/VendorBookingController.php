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
        $hasPackages = $vendor->packages()->exists();

        $data = $request->validateWithBag('booking', [
            'vendor_package_id' => [
                $hasPackages ? 'required' : 'nullable',
                'integer',
                Rule::exists('vendor_packages', 'id')->where('vendor_id', $vendor->id),
            ],
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

        $booking = VendorBooking::create([
            'vendor_id'         => $vendor->id,
            'user_id'           => $request->user()->id,
            'vendor_package_id' => $data['vendor_package_id'] ?? null,
            'agreed_total'      => isset($data['vendor_package_id'])
                ? (int) ($vendor->packages()->whereKey((int) $data['vendor_package_id'])->value('price_raw') ?? 0)
                : null,
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
