<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\VendorBooking;

class PromoService
{
    /**
     * Validasi kode promo dan kembalikan hasilnya sebagai array.
     *
     * @return array{valid: bool, promo: ?Promo, discount: int, message: string}
     */
    public function validate(string $code, int $subtotal, ?int $vendorPackageId = null, ?int $userId = null): array
    {
        $promo = Promo::active()->available()
            ->where('code', strtoupper(trim($code)))
            ->where(fn ($q) => $q
                ->doesntHave('vendorPackages')
                ->orWhereHas('vendorPackages', fn ($q) => $q->whereKey($vendorPackageId))
            )
            ->first();

        if (! $promo) {
            return ['valid' => false, 'promo' => null, 'discount' => 0, 'message' => 'Kode promo tidak ditemukan atau sudah tidak berlaku.'];
        }

        if ($userId && VendorBooking::where('user_id', $userId)->where('promo_code', $promo->code)->exists()) {
            return ['valid' => false, 'promo' => null, 'discount' => 0, 'message' => 'Kode promo ini sudah pernah kamu gunakan.'];
        }

        if ($subtotal < $promo->min_amount) {
            $min = 'Rp ' . number_format($promo->min_amount, 0, ',', '.');
            return ['valid' => false, 'promo' => null, 'discount' => 0, 'message' => "Minimum pemesanan {$min} untuk menggunakan kode ini."];
        }

        $discount = $this->calculateDiscount($promo, $subtotal);

        return [
            'valid'    => true,
            'promo'    => $promo,
            'discount' => $discount,
            'message'  => 'Kode promo berhasil diterapkan.',
        ];
    }

    /**
     * Hitung nominal diskon dari promo terhadap subtotal.
     * Selalu server-side — tidak boleh percaya angka dari client.
     */
    public function calculateDiscount(Promo $promo, int $subtotal): int
    {
        if ($promo->type === 'percentage') {
            $discount = (int) floor($subtotal * $promo->value / 100);

            if ($promo->max_discount !== null) {
                $discount = min($discount, $promo->max_discount);
            }

            return $discount;
        }

        // fixed: tidak boleh melebihi subtotal
        return min((int) $promo->value, $subtotal);
    }

    /**
     * Tandai promo sudah dipakai (increment uses_count).
     * Dipanggil hanya setelah booking berhasil disimpan.
     */
    public function markUsed(Promo $promo): void
    {
        $promo->increment('uses_count');
    }
}
