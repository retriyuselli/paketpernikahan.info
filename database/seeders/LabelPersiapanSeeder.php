<?php

namespace Database\Seeders;

use App\Models\LabelPersiapan;
use Illuminate\Database\Seeder;

class LabelPersiapanSeeder extends Seeder
{
    private array $labels = [
        'lamaran' => [
            'Persiapan Umum',
            'Koordinasi Keluarga',
            'Tamu & Undangan',
            'Seserahan',
            'Cincin Lamaran',
            'Busana',
            'Aksesori & Perhiasan',
            'Katering',
            'Konsumsi',
            'Dekorasi',
            'Sound System',
            'Transportasi',
            'Dokumentasi',
            'Dokumen',
            'Keuangan',
        ],
        'pengajian' => [
            'Persiapan Umum',
            'Koordinasi Keluarga',
            'Tamu & Undangan',
            'Pemimpin Acara',
            'Perlengkapan Ibadah',
            'Konsumsi',
            'Dekorasi',
            'Sound System',
            'Busana',
            'Transportasi',
            'Dokumentasi',
            'Hadiah & Souvenir',
            'Keuangan',
        ],
        'akad' => [
            // Administrasi
            'Dokumen Nikah',
            'KUA & Administrasi',
            'Perizinan',
            // Prosesi
            'Penghulu & Wali',
            'Koordinasi Saksi',
            'Ijab Kabul',
            'Prosesi Akad',
            'Rundown Acara',
            'Perlengkapan Ibadah',
            'Persiapan Spiritual',
            // Mahar & Perhiasan
            'Mahar',
            'Cincin Nikah',
            'Aksesori & Perhiasan',
            // Tempat
            'Lokasi & Venue',
            'Pelaminan',
            'Dekorasi',
            // Konsumsi & Tamu
            'Konsumsi',
            'Tamu & Undangan',
            'Akomodasi Tamu',
            'Koordinasi Keluarga',
            // Penampilan
            'Busana Pengantin',
            'Busana & MUA',
            'Kesehatan & Kecantikan',
            // Pendukung
            'Sound System',
            'Transportasi',
            'Pre-Wedding',
            'Dokumentasi',
            'Koordinasi WO',
            'Keuangan',
        ],
        'resepsi' => [
            // Venue & Tempat
            'Venue & Gedung',
            'Tenda & Fasilitas',
            'Perizinan',
            'Listrik & Generator',
            'Parkir & Keamanan',
            'Kebersihan',
            'Toilet & Fasilitas',
            // Dekorasi
            'Pelaminan',
            'Dekorasi',
            'Dekorasi Pintu Masuk',
            'Dekorasi Meja',
            'Bunga & Rangkaian',
            // Makanan & Minuman
            'Katering',
            'Konsumsi VIP',
            'Konsumsi Panitia',
            // Undangan & Tamu
            'Undangan',
            'Undangan Digital',
            'Tamu & Konfirmasi',
            'Penerima Tamu',
            'Dress Code Tamu',
            'Akomodasi Tamu',
            // Souvenir & Hadiah
            'Souvenir',
            'Doorprize',
            // Penampilan
            'Busana & MUA',
            'Aksesori & Perhiasan',
            'Kesehatan & Kecantikan',
            // Dokumentasi
            'Pre-Wedding',
            'Dokumentasi',
            'Photo Booth',
            // Acara
            'Acara & MC',
            'Hiburan',
            'Live Music / Band',
            'Sound System & Lighting',
            // Logistik
            'Transportasi',
            'Koordinasi Keluarga',
            'Koordinasi Vendor',
            'Koordinasi WO',
            'Koordinasi Katering',
            // Keuangan
            'Pembayaran',
            'Keuangan',
            // Pasca Resepsi
            'Honeymoon',
        ],
    ];

    public function run(): void
    {
        LabelPersiapan::truncate();

        foreach ($this->labels as $jenisAcara => $namaList) {
            foreach ($namaList as $sort => $nama) {
                LabelPersiapan::create([
                    'jenis_acara' => $jenisAcara,
                    'nama'        => $nama,
                    'sort_order'  => $sort + 1,
                ]);
            }
        }

        $total = LabelPersiapan::count();
        $this->command->info("LabelPersiapanSeeder: {$total} label dibuat.");

        $this->command->table(
            ['Jenis Acara', 'Jumlah Label'],
            collect($this->labels)->map(fn ($list, $jenis) => [
                ucfirst($jenis), count($list),
            ])->values()->toArray()
        );
    }
}
