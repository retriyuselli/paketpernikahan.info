<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorBookingPaymentController extends Controller
{
    public function store(Request $request, VendorBooking $booking)
    {
        $user = $request->user();
        abort_unless($user && (int) $booking->user_id === (int) $user->id, 403);

        $data = $request->validateWithBag('payment', [
            'type'        => ['required', 'in:dp,final,installment'],
            'amount'      => ['required', 'integer', 'min:0'],
            'method'      => ['required', 'in:transfer,qris,cash,other'],
            'sender_name' => ['nullable', 'string', 'max:120'],
            'sender_bank' => ['nullable', 'string', 'max:80'],
            'paid_at'     => ['nullable', 'date'],
            'proof'       => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $path = $request->file('proof')->store('payments', 'public');

        VendorBookingPayment::create([
            'vendor_booking_id' => $booking->id,
            'type'              => $data['type'],
            'amount'            => (int) $data['amount'],
            'method'            => $data['method'],
            'sender_name'       => $data['sender_name'] ?? null,
            'sender_bank'       => $data['sender_bank'] ?? null,
            'paid_at'           => $data['paid_at'] ?? null,
            'proof_path'        => $path,
            'status'            => 'pending_verification',
        ]);

        $this->recalcBookingPaymentStatus($booking);

        return redirect()
            ->route('dashboard.booking.payment', $booking)
            ->with('payment_success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi.');
    }

    public function verify(Request $request, VendorBookingPayment $payment)
    {
        $user = User::find(Auth::id());
        abort_unless($user, 403);

        $booking = $payment->booking()->with('vendor')->firstOrFail();

        $isAdmin = $user->hasRole(['super_admin', 'admin']);
        $isVendorOwner = $user->hasRole(['vendor']) && (int) $booking->vendor?->owner_user_id === (int) $user->id;
        abort_unless($isAdmin || $isVendorOwner, 403);

        $data = $request->validateWithBag('payment_verify', [
            'action' => ['required', 'in:approve,reject'],
            'note'   => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'approve') {
            $payment->update([
                'status' => 'approved',
                'verified_by' => $user->id,
                'verified_at' => now(),
                'note' => $data['note'] ?? null,
            ]);
        } else {
            $note = trim((string) ($data['note'] ?? ''));
            if ($note === '') {
                return back()
                    ->withErrors(['note' => 'Catatan wajib diisi saat reject.'], 'payment_verify');
            }
            $payment->update([
                'status' => 'rejected',
                'verified_by' => $user->id,
                'verified_at' => now(),
                'note' => $note,
            ]);
        }

        $this->recalcBookingPaymentStatus($booking);

        return back()->with('payment_success', 'Status pembayaran berhasil diperbarui.');
    }

    private function recalcBookingPaymentStatus(VendorBooking $booking): void
    {
        $hasApprovedFinal = $booking->payments()
            ->where('status', 'approved')
            ->whereIn('type', ['final', 'installment'])
            ->exists();

        if ($hasApprovedFinal) {
            $booking->update(['payment_status' => 'paid']);
            return;
        }

        $hasApprovedDp = $booking->payments()
            ->where('status', 'approved')
            ->where('type', 'dp')
            ->exists();

        if ($hasApprovedDp) {
            $booking->update(['payment_status' => 'dp_paid']);
            return;
        }

        $hasPending = $booking->payments()
            ->where('status', 'pending_verification')
            ->exists();

        $booking->update(['payment_status' => $hasPending ? 'dp_waiting' : 'unpaid']);
    }
}
