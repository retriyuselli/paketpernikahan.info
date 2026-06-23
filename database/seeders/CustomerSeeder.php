<?php

namespace Database\Seeders;

use App\Models\CustomerNotification;
use App\Models\CustomerPaymentMethod;
use App\Models\CustomerPreparationSection;
use App\Models\CustomerPreparationTask;
use App\Models\FamilyMember;
use App\Models\User;
use App\Models\WeddingEvent;
use App\Models\WeddingInfo;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan demo user sebagai customer utama
        $demo = User::where('email', 'demo@paketpernikahan.com')->first();

        // Ambil 2 user pengunjung tambahan sebagai customer sampel
        $extras = User::role(['customer', 'pengunjung'])
            ->where('email', '!=', 'demo@paketpernikahan.com')
            ->take(2)
            ->get();

        $customers = $extras->prepend($demo)->filter();

        if ($customers->isEmpty()) {
            $this->command->warn('CustomerSeeder: tidak ada user pengunjung ditemukan, skip.');
            return;
        }

        foreach ($customers as $user) {
            $this->seedWeddingInfo($user);
            $this->seedWeddingEvents($user);
            $this->seedFamilyMembers($user);
            $this->seedPaymentMethods($user);
            $this->seedNotifications($user);
            $this->seedPreparation($user);
        }

        $this->command->info('CustomerSeeder: data customer berhasil di-seed untuk ' . $customers->count() . ' user.');
    }

    // ─────────────────────────────────────────
    // Wedding Info
    // ─────────────────────────────────────────

    private function seedWeddingInfo(User $user): void
    {
        $samples = [
            ['groom_name' => 'Budi Santoso',    'bride_name' => 'Sari Dewi Lestari'],
            ['groom_name' => 'Rizky Pratama',   'bride_name' => 'Anisa Rahmawati'],
            ['groom_name' => 'Dimas Ardiansyah','bride_name' => 'Putri Maharani'],
        ];

        static $idx = 0;
        $data = $samples[$idx % count($samples)];
        $idx++;

        WeddingInfo::updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );
    }

    // ─────────────────────────────────────────
    // Wedding Events
    // ─────────────────────────────────────────

    private function seedWeddingEvents(User $user): void
    {
        WeddingEvent::where('user_id', $user->id)->delete();

        // Ambil booking pertama milik user ini (jika ada) untuk dihubungkan ke resepsi
        $booking = \App\Models\VendorBooking::where('user_id', $user->id)->first();

        $events = [
            [
                'jenis_acara'  => 'lamaran',
                'tgl_acara'    => now()->addDays(30)->toDateString(),
                'lokasi_acara' => 'Kediaman Pengantin Wanita, Jl. Melati No. 5, Jakarta Selatan',
                'catatan'      => 'Acara lamaran keluarga inti, dihadiri kedua pihak keluarga.',
            ],
            [
                'jenis_acara'  => 'pengajian',
                'tgl_acara'    => now()->addDays(60)->toDateString(),
                'lokasi_acara' => 'Masjid Al-Ikhlas, Jl. Sudirman No. 10, Jakarta Pusat',
                'catatan'      => 'Pengajian dan doa bersama sebelum pernikahan.',
            ],
            [
                'jenis_acara'       => 'akad',
                'tgl_acara'         => now()->addDays(90)->toDateString(),
                'lokasi_acara'      => 'Masjid Al-Ikhlas, Jl. Sudirman No. 10, Jakarta Pusat',
                'vendor_booking_id' => null,
                'catatan'           => 'Akad nikah pukul 08.00 WIB, dilanjutkan sungkeman.',
            ],
            [
                'jenis_acara'       => 'resepsi',
                'tgl_acara'         => now()->addDays(90)->toDateString(),
                'lokasi_acara'      => 'Grand Ballroom Hotel Mulia, Jl. Asia Afrika, Jakarta',
                'vendor_booking_id' => $booking?->id,
                'catatan'           => 'Resepsi pukul 11.00 – 15.00 WIB. Kapasitas 500 tamu.',
            ],
        ];

        foreach ($events as $event) {
            WeddingEvent::create(array_merge($event, ['user_id' => $user->id]));
        }
    }

    // ─────────────────────────────────────────
    // Family Members
    // ─────────────────────────────────────────

    private function seedFamilyMembers(User $user): void
    {
        // Hapus dulu agar tidak duplikat saat re-seed
        FamilyMember::where('user_id', $user->id)->delete();

        $members = [
            ['name' => 'Bapak Santoso',      'role' => 'Ayah Pengantin Pria',    'phone' => '6281234561001'],
            ['name' => 'Ibu Rahayu',         'role' => 'Ibu Pengantin Pria',     'phone' => '6281234561002'],
            ['name' => 'Bapak Hendra',       'role' => 'Ayah Pengantin Wanita',  'phone' => '6281234561003'],
            ['name' => 'Ibu Wati',           'role' => 'Ibu Pengantin Wanita',   'phone' => '6281234561004'],
            ['name' => 'Andi Santoso',       'role' => 'Kakak Pengantin Pria',   'phone' => null],
        ];

        foreach ($members as $member) {
            FamilyMember::create(array_merge($member, ['user_id' => $user->id]));
        }
    }

    // ─────────────────────────────────────────
    // Payment Methods
    // ─────────────────────────────────────────

    private function seedPaymentMethods(User $user): void
    {
        CustomerPaymentMethod::where('user_id', $user->id)->delete();

        $methods = [
            [
                'name'           => 'BCA',
                'logo_icon'      => 'building.columns.fill',
                'account_number' => '1234567890',
                'account_name'   => $user->name,
                'is_primary'     => true,
                'type'           => 'bank',
            ],
            [
                'name'           => 'GoPay',
                'logo_icon'      => 'g.circle.fill',
                'account_number' => '628' . rand(10000000, 99999999),
                'account_name'   => $user->name,
                'is_primary'     => false,
                'type'           => 'ewallet',
            ],
        ];

        foreach ($methods as $method) {
            CustomerPaymentMethod::create(array_merge($method, ['user_id' => $user->id]));
        }
    }

    // ─────────────────────────────────────────
    // Notifications
    // ─────────────────────────────────────────

    private function seedNotifications(User $user): void
    {
        CustomerNotification::where('user_id', $user->id)->delete();

        $notifications = [
            [
                'group'       => 'Booking',
                'title'       => 'Booking Dikonfirmasi',
                'message'     => 'Booking Anda untuk Paket Platinum telah dikonfirmasi oleh vendor.',
                'icon'        => 'checkmark.circle.fill',
                'destination' => 'payment',
                'tint'        => '#34C759',
                'is_unread'   => true,
            ],
            [
                'group'       => 'Pembayaran',
                'title'       => 'Tagihan DP Jatuh Tempo',
                'message'     => 'Tagihan DP sebesar Rp 5.000.000 akan jatuh tempo dalam 3 hari.',
                'icon'        => 'creditcard.fill',
                'destination' => 'payment',
                'tint'        => '#FF9500',
                'is_unread'   => true,
            ],
            [
                'group'       => 'Persiapan',
                'title'       => 'Tugas Belum Selesai',
                'message'     => 'Anda memiliki 5 tugas persiapan yang belum diselesaikan minggu ini.',
                'icon'        => 'checklist',
                'destination' => 'preparation',
                'tint'        => '#007AFF',
                'is_unread'   => true,
            ],
            [
                'group'       => 'Promo',
                'title'       => 'Promo Spesial Untukmu!',
                'message'     => 'Dapatkan diskon 10% untuk paket foto prewedding. Gunakan kode FOTO10.',
                'icon'        => 'tag.fill',
                'destination' => 'store',
                'tint'        => '#FF2D55',
                'is_unread'   => false,
            ],
            [
                'group'       => 'Sistem',
                'title'       => 'Selamat Datang!',
                'message'     => 'Akun Anda berhasil dibuat. Mulai rencanakan pernikahan impian Anda.',
                'icon'        => 'heart.fill',
                'destination' => null,
                'tint'        => '#F5527F',
                'is_unread'   => false,
            ],
        ];

        foreach ($notifications as $notification) {
            CustomerNotification::create(array_merge($notification, ['user_id' => $user->id]));
        }
    }

    // ─────────────────────────────────────────
    // Preparation Sections & Tasks
    // ─────────────────────────────────────────

    private function seedPreparation(User $user): void
    {
        CustomerPreparationSection::where('user_id', $user->id)->each(function ($section) {
            $section->tasks()->delete();
            $section->delete();
        });

        $sections = [
            [
                'title'      => 'Venue & Dekorasi',
                'icon'       => 'building.2.fill',
                'sort_order' => 1,
                'tasks'      => [
                    ['title' => 'Survey dan pilih venue',              'status' => 'done',    'due_date' => now()->subDays(30)->toDateString()],
                    ['title' => 'Konfirmasi dekorasi dengan vendor',   'status' => 'done',    'due_date' => now()->subDays(20)->toDateString()],
                    ['title' => 'Bayar DP venue',                      'status' => 'done',    'due_date' => now()->subDays(15)->toDateString()],
                    ['title' => 'Final fitting dekorasi',              'status' => 'pending', 'due_date' => now()->addDays(14)->toDateString()],
                    ['title' => 'Pelunasan biaya venue',               'status' => 'todo',    'due_date' => now()->addDays(30)->toDateString()],
                ],
            ],
            [
                'title'      => 'Catering',
                'icon'       => 'fork.knife',
                'sort_order' => 2,
                'tasks'      => [
                    ['title' => 'Pilih menu catering',                 'status' => 'done',    'due_date' => now()->subDays(25)->toDateString()],
                    ['title' => 'Food tasting dengan catering vendor', 'status' => 'done',    'due_date' => now()->subDays(10)->toDateString()],
                    ['title' => 'Konfirmasi jumlah porsi',             'status' => 'pending', 'due_date' => now()->addDays(7)->toDateString()],
                    ['title' => 'Pelunasan catering',                  'status' => 'todo',    'due_date' => now()->addDays(25)->toDateString()],
                ],
            ],
            [
                'title'      => 'Fotografer & Videografer',
                'icon'       => 'camera.fill',
                'sort_order' => 3,
                'tasks'      => [
                    ['title' => 'Pilih fotografer dan paket foto',     'status' => 'done',    'due_date' => now()->subDays(28)->toDateString()],
                    ['title' => 'Sesi foto prewedding',                'status' => 'pending', 'due_date' => now()->addDays(5)->toDateString()],
                    ['title' => 'Diskusi konsep foto hari-H',          'status' => 'todo',    'due_date' => now()->addDays(20)->toDateString()],
                    ['title' => 'Pelunasan fotografer',                'status' => 'todo',    'due_date' => now()->addDays(28)->toDateString()],
                ],
            ],
            [
                'title'      => 'Busana Pengantin',
                'icon'       => 'tshirt.fill',
                'sort_order' => 4,
                'tasks'      => [
                    ['title' => 'Pilih gaun pengantin',                'status' => 'done',    'due_date' => now()->subDays(20)->toDateString()],
                    ['title' => 'Fitting pertama gaun',                'status' => 'done',    'due_date' => now()->subDays(10)->toDateString()],
                    ['title' => 'Fitting kedua dan finalisasi',        'status' => 'todo',    'due_date' => now()->addDays(10)->toDateString()],
                    ['title' => 'Ambil gaun sebelum hari-H',           'status' => 'todo',    'due_date' => now()->addDays(1)->toDateString()],
                ],
            ],
            [
                'title'      => 'Undangan & Souvenir',
                'icon'       => 'envelope.fill',
                'sort_order' => 5,
                'tasks'      => [
                    ['title' => 'Desain undangan',                     'status' => 'done',    'due_date' => now()->subDays(22)->toDateString()],
                    ['title' => 'Cetak undangan fisik',                'status' => 'done',    'due_date' => now()->subDays(8)->toDateString()],
                    ['title' => 'Kirim undangan digital',              'status' => 'pending', 'due_date' => now()->addDays(3)->toDateString()],
                    ['title' => 'Pesan souvenir tamu',                 'status' => 'pending', 'due_date' => now()->addDays(12)->toDateString()],
                    ['title' => 'Distribusi undangan fisik',           'status' => 'todo',    'due_date' => now()->addDays(15)->toDateString()],
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            $tasks = $sectionData['tasks'];
            unset($sectionData['tasks']);

            $section = CustomerPreparationSection::create(
                array_merge($sectionData, ['user_id' => $user->id])
            );

            foreach ($tasks as $sort => $task) {
                CustomerPreparationTask::create(array_merge($task, [
                    'section_id' => $section->id,
                    'user_id'    => $user->id,
                    'sort_order' => $sort + 1,
                ]));
            }
        }
    }
}
