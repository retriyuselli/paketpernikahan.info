<?php

namespace App\Services;

use App\Models\User;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(private BookingService $bookingService) {}

    /**
     * Simpan bukti pembayaran dan update payment status booking.
     */
    public function uploadPayment(VendorBooking $booking, array $data, UploadedFile $proof): VendorBookingPayment
    {
        return DB::transaction(function () use ($booking, $data, $proof) {
            $path = $proof->store('payments', 'public');

            $payment = VendorBookingPayment::create([
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

            $this->bookingService->recalcPaymentStatus($booking);

            return $payment;
        });
    }

    /**
     * Approve atau reject payment, lalu recalc status booking.
     * Reject wajib disertai catatan.
     */
    public function verifyPayment(VendorBookingPayment $payment, User $verifier, string $action, ?string $note): void
    {
        DB::transaction(function () use ($payment, $verifier, $action, $note) {
            $payment->update([
                'status'      => $action === 'approve' ? 'approved' : 'rejected',
                'verified_by' => $verifier->id,
                'verified_at' => now(),
                'note'        => $note,
            ]);

            $booking = $payment->booking()->first();
            $this->bookingService->recalcPaymentStatus($booking);
        });
    }
}
