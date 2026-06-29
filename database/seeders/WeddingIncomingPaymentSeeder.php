<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeddingIncomingPayment;
use Illuminate\Database\Seeder;

class WeddingIncomingPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@paketpernikahan.com')->first();

        $extras = User::role(['customer', 'pengunjung'])
            ->where('email', '!=', 'demo@paketpernikahan.com')
            ->orderBy('name')
            ->get();

        $customers = $extras->prepend($demo)->filter()->values();

        if ($customers->isEmpty()) {
            $this->command->warn('WeddingIncomingPaymentSeeder: tidak ada user customer ditemukan, skip.');
            return;
        }

        foreach ($customers as $index => $user) {
            $this->seedIncomingPayments($user, $index);
        }

        $this->command->info('WeddingIncomingPaymentSeeder: uang masuk berhasil di-seed untuk ' . $customers->count() . ' user.');
    }

    private function seedIncomingPayments(User $user, int $index): void
    {
        $offset = $index * 3;

        $samples = [
            [
                'bank_name'        => 'Tabungan Pribadi',
                'amount'           => 50_000_000,
                'transfer_date'    => now()->subDays(90 + $offset)->toDateString(),
                'sender_name'      => $user->name,
                'description'      => 'Modal awal budget pernikahan untuk DP venue dan dekorasi',
                'reference_number' => $this->referenceNumber($user, 1),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(89 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Dana pribadi sudah masuk ke kas pernikahan.',
            ],
            [
                'bank_name'        => 'Orang Tua Mempelai Wanita',
                'amount'           => 25_000_000,
                'transfer_date'    => now()->subDays(75 + $offset)->toDateString(),
                'sender_name'      => 'Ayah dan Ibu Mempelai Wanita',
                'description'      => 'Tambahan dana keluarga untuk termin 1 catering',
                'reference_number' => $this->referenceNumber($user, 2),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(74 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Dialokasikan untuk pembayaran vendor catering.',
            ],
            [
                'bank_name'        => 'Orang Tua Mempelai Pria',
                'amount'           => 18_000_000,
                'transfer_date'    => now()->subDays(62 + $offset)->toDateString(),
                'sender_name'      => 'Keluarga Mempelai Pria',
                'description'      => 'Dana keluarga untuk DP dokumentasi foto dan video',
                'reference_number' => $this->referenceNumber($user, 3),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(61 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Bukti dana sudah sesuai nominal yang dicatat.',
            ],
            [
                'bank_name'        => 'Simpanan Bersama',
                'amount'           => 12_000_000,
                'transfer_date'    => now()->subDays(50 + $offset)->toDateString(),
                'sender_name'      => $user->name . ' dan Pasangan',
                'description'      => 'Simpanan bersama untuk DP makeup dan busana',
                'reference_number' => $this->referenceNumber($user, 4),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(49 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Dana sudah dikonfirmasi oleh finance.',
            ],
            [
                'bank_name'        => 'Bonus Kerja',
                'amount'           => 10_000_000,
                'transfer_date'    => now()->subDays(42 + $offset)->toDateString(),
                'sender_name'      => $user->name,
                'description'      => 'Bonus kerja dialokasikan untuk entertainment',
                'reference_number' => $this->referenceNumber($user, 5),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(41 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Dialokasikan untuk live music dan MC.',
            ],
            [
                'bank_name'        => 'Hadiah Keluarga',
                'amount'           => 7_500_000,
                'transfer_date'    => now()->subDays(31 + $offset)->toDateString(),
                'sender_name'      => 'Keluarga Besar',
                'description'      => 'Hadiah keluarga untuk transport keluarga dan tamu VIP',
                'reference_number' => $this->referenceNumber($user, 6),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(30 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Dana diterima penuh.',
            ],
            [
                'bank_name'        => 'Simpanan Bulanan',
                'amount'           => 20_000_000,
                'transfer_date'    => now()->subDays(24 + $offset)->toDateString(),
                'sender_name'      => $user->name . ' dan Pasangan',
                'description'      => 'Akumulasi simpanan bulanan untuk termin 2 catering',
                'reference_number' => $this->referenceNumber($user, 7),
                'status'           => 'confirmed',
                'confirmed_at'     => now()->subDays(23 + $offset),
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'notes'            => 'Termin catering kedua.',
            ],
            [
                'bank_name'        => 'Dana Cadangan',
                'amount'           => 8_000_000,
                'transfer_date'    => now()->subDays(16 + $offset)->toDateString(),
                'sender_name'      => $user->name,
                'description'      => 'Dana cadangan untuk tambahan dekorasi area foyer',
                'reference_number' => $this->referenceNumber($user, 8),
                'status'           => 'pending',
                'confirmed_at'     => null,
                'confirmed_by'     => null,
                'notes'            => 'Menunggu pengecekan bukti dana masuk.',
            ],
            [
                'bank_name'        => 'Hadiah Teman',
                'amount'           => 6_500_000,
                'transfer_date'    => now()->subDays(10 + $offset)->toDateString(),
                'sender_name'      => 'Teman Dekat',
                'description'      => 'Hadiah dari teman untuk DP souvenir',
                'reference_number' => $this->referenceNumber($user, 9),
                'status'           => 'pending',
                'confirmed_at'     => null,
                'confirmed_by'     => null,
                'notes'            => 'Bukti dana belum diverifikasi.',
            ],
            [
                'bank_name'        => 'Arisan Keluarga',
                'amount'           => 4_000_000,
                'transfer_date'    => now()->subDays(6 + $offset)->toDateString(),
                'sender_name'      => 'Koordinator Arisan Keluarga',
                'description'      => 'Dana arisan keluarga untuk tambahan konsumsi panitia',
                'reference_number' => $this->referenceNumber($user, 10),
                'status'           => 'pending',
                'confirmed_at'     => null,
                'confirmed_by'     => null,
                'notes'            => 'Menunggu konfirmasi admin.',
            ],
            [
                'bank_name'        => 'Pinjaman Keluarga',
                'amount'           => 3_000_000,
                'transfer_date'    => now()->subDays(4 + $offset)->toDateString(),
                'sender_name'      => 'Saudara Keluarga',
                'description'      => 'Pinjaman keluarga belum sesuai kesepakatan pencatatan',
                'reference_number' => $this->referenceNumber($user, 11),
                'status'           => 'rejected',
                'confirmed_at'     => null,
                'confirmed_by'     => 'Admin Paket Pernikahan',
                'rejection_reason' => 'Detail sumber dana belum lengkap.',
                'notes'            => 'Customer diminta melengkapi catatan dan bukti dana.',
            ],
            [
                'bank_name'        => 'Penjualan Aset',
                'amount'           => 30_000_000,
                'transfer_date'    => now()->subDays(1 + $offset)->toDateString(),
                'sender_name'      => $user->name,
                'description'      => 'Dana hasil penjualan aset untuk pelunasan vendor',
                'reference_number' => $this->referenceNumber($user, 12),
                'status'           => 'pending',
                'confirmed_at'     => null,
                'confirmed_by'     => null,
                'notes'            => 'Menunggu pengecekan terakhir.',
            ],
        ];

        foreach ($samples as $sample) {
            WeddingIncomingPayment::updateOrCreate(
                [
                    'user_id'          => $user->id,
                    'reference_number' => $sample['reference_number'],
                ],
                array_merge($sample, [
                    'user_id' => $user->id,
                ]),
            );
        }
    }

    private function referenceNumber(User $user, int $sequence): string
    {
        return 'WIP-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
