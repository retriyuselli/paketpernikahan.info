<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorBookingPaymentController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private PaymentService $paymentService,
    ) {}

    public function store(Request $request, VendorBooking $booking)
    {
        $user = $request->user();
        abort_unless($user && (int) $booking->user_id === (int) $user->id, 403);

        $data = $request->validateWithBag('payment', [
            'type'        => ['required', 'in:dp,final,installment'],
            'amount'      => ['required', 'integer', 'min:0'],
            'method'      => ['required', 'in:transfer,qris,cash,other'],
            'sender_name' => ['required', 'string', 'max:120'],
            'sender_bank' => ['required', 'string', 'max:80'],
            'paid_at'     => ['nullable', 'date'],
            'proof'       => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $dpError = $this->bookingService->validateDpAmount($booking, $data['type'], (int) $data['amount']);
        if ($dpError) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $dpError, 'errors' => ['amount' => [$dpError]]], 422);
            }

            return back()->withErrors(['amount' => $dpError], 'payment')->withInput();
        }

        $this->paymentService->uploadPayment($booking, $data, $request->file('proof'));

        return redirect()
            ->route('dashboard.booking')
            ->with('booking_success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi.');
    }

    public function verify(Request $request, VendorBookingPayment $payment)
    {
        $user = User::find(Auth::id());
        abort_unless($user, 403);

        $booking = $payment->booking()->with('vendor')->firstOrFail();

        $isAdmin       = $user->hasRole(['super_admin', 'admin']);
        $isVendorOwner = $user->hasRole(['vendor']) && (int) $booking->vendor?->owner_user_id === (int) $user->id;
        abort_unless($isAdmin || $isVendorOwner, 403);

        $data = $request->validateWithBag('payment_verify', [
            'action' => ['required', 'in:approve,reject'],
            'note'   => ['nullable', 'string', 'max:2000'],
        ]);

        $note = trim((string) ($data['note'] ?? ''));

        if ($data['action'] === 'reject' && $note === '') {
            return back()->withErrors(['note' => 'Catatan wajib diisi saat reject.'], 'payment_verify');
        }

        $this->paymentService->verifyPayment($payment, $user, $data['action'], $note ?: null);

        return back()->with('payment_success', 'Status pembayaran berhasil diperbarui.');
    }
}
