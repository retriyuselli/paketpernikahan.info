<?php

namespace Database\Seeders;

use App\Models\WeddingPaymentScheduleTemplate;
use Illuminate\Database\Seeder;

class WeddingPaymentScheduleTemplateSeeder extends Seeder
{
    private array $templates = [
        'lamaran' => [
            ['title' => 'DP Venue Lamaran', 'vendor_name' => 'Venue Lamaran', 'category' => 'venue', 'amount' => 5_000_000, 'due_days_before_event' => 45, 'notes' => 'Booking tempat acara lamaran.'],
            ['title' => 'Katering Lamaran', 'vendor_name' => 'Vendor Katering', 'category' => 'catering', 'amount' => 8_000_000, 'due_days_before_event' => 21, 'notes' => 'Konsumsi keluarga inti dan tamu lamaran.'],
            ['title' => 'Dekorasi Lamaran', 'vendor_name' => 'Vendor Dekorasi', 'category' => 'decoration', 'amount' => 6_000_000, 'due_days_before_event' => 14, 'notes' => 'Dekorasi backdrop dan area prosesi lamaran.'],
        ],
        'pengajian' => [
            ['title' => 'Konsumsi Pengajian', 'vendor_name' => 'Vendor Konsumsi', 'category' => 'catering', 'amount' => 6_000_000, 'due_days_before_event' => 21, 'notes' => 'Snack box dan makan tamu pengajian.'],
            ['title' => 'Sound System Pengajian', 'vendor_name' => 'Vendor Sound System', 'category' => 'entertainment', 'amount' => 2_500_000, 'due_days_before_event' => 14, 'notes' => 'Mic, speaker, dan teknisi acara pengajian.'],
            ['title' => 'Perlengkapan Pengajian', 'vendor_name' => 'Vendor Perlengkapan', 'category' => 'other', 'amount' => 3_000_000, 'due_days_before_event' => 7, 'notes' => 'Buku Yasin, souvenir, dan perlengkapan ibadah.'],
        ],
        'akad' => [
            ['title' => 'Biaya KUA / Penghulu', 'vendor_name' => 'KUA / Penghulu', 'category' => 'other', 'amount' => 1_500_000, 'due_days_before_event' => 60, 'notes' => 'Administrasi akad nikah.'],
            ['title' => 'Makeup Akad', 'vendor_name' => 'Vendor Makeup', 'category' => 'makeup', 'amount' => 10_000_000, 'due_days_before_event' => 30, 'notes' => 'Makeup dan busana akad.'],
            ['title' => 'Dokumentasi Akad', 'vendor_name' => 'Vendor Foto & Video', 'category' => 'photo_video', 'amount' => 12_000_000, 'due_days_before_event' => 21, 'notes' => 'Dokumentasi akad nikah.'],
        ],
        'resepsi' => [
            ['title' => 'Pelunasan Venue Resepsi', 'vendor_name' => 'Venue Resepsi', 'category' => 'venue', 'amount' => 35_000_000, 'due_days_before_event' => 60, 'notes' => 'Pelunasan gedung atau ballroom resepsi.'],
            ['title' => 'Pelunasan Katering Resepsi', 'vendor_name' => 'Vendor Katering', 'category' => 'catering', 'amount' => 45_000_000, 'due_days_before_event' => 30, 'notes' => 'Pelunasan katering resepsi.'],
            ['title' => 'Dekorasi Resepsi', 'vendor_name' => 'Vendor Dekorasi', 'category' => 'decoration', 'amount' => 25_000_000, 'due_days_before_event' => 21, 'notes' => 'Dekorasi pelaminan dan area resepsi.'],
            ['title' => 'Entertainment Resepsi', 'vendor_name' => 'Vendor Entertainment', 'category' => 'entertainment', 'amount' => 12_000_000, 'due_days_before_event' => 14, 'notes' => 'MC, band, dan sound system resepsi.'],
            ['title' => 'Transportasi Tamu VIP', 'vendor_name' => 'Vendor Transportasi', 'category' => 'transport', 'amount' => 5_000_000, 'due_days_before_event' => 7, 'notes' => 'Transportasi keluarga dan tamu VIP.'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->templates as $jenisAcara => $templates) {
            foreach ($templates as $index => $template) {
                WeddingPaymentScheduleTemplate::updateOrCreate(
                    [
                        'jenis_acara' => $jenisAcara,
                        'title'       => $template['title'],
                    ],
                    array_merge($template, [
                        'sort_order' => $index + 1,
                        'is_active'  => true,
                    ]),
                );
            }
        }

        $this->command->info('WeddingPaymentScheduleTemplateSeeder: ' . WeddingPaymentScheduleTemplate::count() . ' template payment schedule berhasil dibuat.');
    }
}
