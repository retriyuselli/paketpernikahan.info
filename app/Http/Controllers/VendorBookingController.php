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
        $data = $request->validateWithBag('booking', [
            'vendor_package_id' => [
                'nullable',
                'integer',
                Rule::exists('vendor_packages', 'id')->where('vendor_id', $vendor->id),
            ],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'phone'      => ['required', 'string', 'max:30'],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ]);

        VendorBooking::create([
            'vendor_id'         => $vendor->id,
            'user_id'           => $request->user()->id,
            'vendor_package_id' => $data['vendor_package_id'] ?? null,
            'event_date'        => $data['event_date'],
            'phone'             => $data['phone'],
            'notes'             => $data['notes'] ?? null,
            'status'            => 'pending',
        ]);

        return back()->with('booking_success', 'Booking berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}

