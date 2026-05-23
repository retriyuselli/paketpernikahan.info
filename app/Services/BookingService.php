<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\Vendor;
use App\Models\VendorBooking;
use App\Models\VendorPackage;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(private PromoService $promoService) {}

    /**
     * Hitung agreed_total dan dp_required_amount dari package + qty + promo opsional.
     * Selalu baca dari DB — tidak pernah percaya angka dari client.
     *
     * @return array{agreed_total: int, dp_required_amount: int|null, promo_discount: int}
     */
    public function calculatePricing(VendorPackage $package, int $qty, ?Promo $promo = null): array
    {
        $price     = (int) ($package->price ?? 0);
        $discount  = (int) ($package->discount ?? 0);
        $unitFinal = max($price - $discount, 0);
        $subtotal  = $unitFinal * $qty;

        $promoDiscount = $promo ? $this->promoService->calculateDiscount($promo, $subtotal) : 0;
        $agreedTotal   = max($subtotal - $promoDiscount, 0);

        $dp = (int) ($package->dp_paket ?? 0);

        return [
            'agreed_total'       => $agreedTotal,
            'dp_required_amount' => $dp > 0 ? ($dp * $qty) : null,
            'promo_discount'     => $promoDiscount,
        ];
    }

    /**
     * Buat booking baru dengan harga dihitung server-side.
     * Promo di-increment uses_count di sini setelah booking tersimpan.
     */
    public function createBooking(Vendor $vendor, \App\Models\User $user, array $data, ?Promo $promo = null): VendorBooking
    {
        return DB::transaction(function () use ($vendor, $user, $data, $promo) {
            $qty = max(1, min(99, (int) ($data['qty'] ?? 1)));

            $package = isset($data['vendor_package_id'])
                ? $vendor->packages()
                    ->select(['id', 'price', 'discount', 'dp_paket'])
                    ->whereKey((int) $data['vendor_package_id'])
                    ->first()
                : null;

            $pricing = $package
                ? $this->calculatePricing($package, $qty, $promo)
                : ['agreed_total' => null, 'dp_required_amount' => null, 'promo_discount' => 0];

            $booking = VendorBooking::create([
                'vendor_id'          => $vendor->id,
                'user_id'            => $user->id,
                'vendor_package_id'  => $data['vendor_package_id'] ?? null,
                'agreed_total'       => $pricing['agreed_total'],
                'dp_required_amount' => $pricing['dp_required_amount'],
                'promo_code'         => $promo?->code,
                'promo_discount'     => $pricing['promo_discount'] > 0 ? $pricing['promo_discount'] : null,
                'event_date'         => $data['event_date'],
                'phone'              => $data['phone'],
                'notes'              => $data['notes'] ?? null,
                'status'             => 'pending',
            ]);

            if ($promo) {
                $this->promoService->markUsed($promo);
            }

            return $booking;
        });
    }

    /**
     * State machine payment status booking.
     * Dipanggil setiap kali ada payment baru atau verifikasi payment.
     */
    public function recalcPaymentStatus(VendorBooking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $hasApprovedFinal = $booking->payments()
                ->where('status', 'approved')
                ->whereIn('type', ['final', 'installment'])
                ->exists();

            if ($hasApprovedFinal) {
                $updates = ['payment_status' => 'paid'];
                if ($booking->status === 'pending') {
                    $updates['status'] = 'confirmed';
                }
                $booking->update($updates);
                return;
            }

            $hasApprovedDp = $booking->payments()
                ->where('status', 'approved')
                ->where('type', 'dp')
                ->exists();

            if ($hasApprovedDp) {
                $updates = ['payment_status' => 'dp_paid'];
                if ($booking->status === 'pending') {
                    $updates['status'] = 'confirmed';
                }
                $booking->update($updates);
                return;
            }

            $hasPending = $booking->payments()
                ->where('status', 'pending_verification')
                ->exists();

            $booking->update(['payment_status' => $hasPending ? 'dp_waiting' : 'unpaid']);
        });
    }

    /**
     * Validasi nominal DP: harus exact match dengan dp_required_amount.
     * Return pesan error, atau null jika valid.
     */
    public function validateDpAmount(VendorBooking $booking, string $type, int $amount): ?string
    {
        if ($type !== 'dp') {
            return null;
        }

        $booking->loadMissing(['vendorPackage']);
        $dpRequired = (int) ($booking->dp_required_amount ?? ($booking->vendorPackage?->dp_paket ?? 0));

        if ($dpRequired > 0 && $amount !== $dpRequired) {
            return 'Nominal DP harus sama dengan DP paket (Rp ' . number_format($dpRequired, 0, ',', '.') . ').';
        }

        return null;
    }
}
