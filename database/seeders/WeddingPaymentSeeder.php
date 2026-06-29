<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeddingBudget;
use App\Models\WeddingEvent;
use App\Models\WeddingPaymentSchedule;
use App\Services\WeddingPaymentScheduleTemplateService;
use Illuminate\Database\Seeder;

class WeddingPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@paketpernikahan.com')->first();

        $extras = User::role(['customer', 'pengunjung'])
            ->where('email', '!=', 'demo@paketpernikahan.com')
            ->take(2)
            ->get();

        $customers = $extras->prepend($demo)->filter()->values();

        if ($customers->isEmpty()) {
            $this->command->warn('WeddingPaymentSeeder: tidak ada user customer ditemukan, skip.');
            return;
        }

        foreach ($customers as $index => $user) {
            $this->seedBudget($user, $index);
            $this->seedSchedulesFromEventTemplates($user);
            $this->seedSampleSchedules($user);
        }

        $this->command->info('WeddingPaymentSeeder: budget dan jadwal pembayaran berhasil di-seed untuk ' . $customers->count() . ' user.');
    }

    private function seedBudget(User $user, int $index): void
    {
        $budgets = [
            150_000_000,
            120_000_000,
            180_000_000,
        ];

        WeddingBudget::updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_budget' => $budgets[$index % count($budgets)],
                'currency'     => 'IDR',
                'notes'        => 'Budget awal pernikahan untuk simulasi pembayaran customer.',
            ],
        );
    }

    private function seedSchedulesFromEventTemplates(User $user): void
    {
        $service = app(WeddingPaymentScheduleTemplateService::class);

        WeddingEvent::where('user_id', $user->id)
            ->orderBy('tgl_acara')
            ->get()
            ->each(fn (WeddingEvent $event) => $service->createSchedulesForEvent($event));
    }

    private function seedSampleSchedules(User $user): void
    {
        $schedules = [
            [
                'title'          => 'Booking Venue',
                'vendor_name'    => 'Grand Ballroom Hotel Mulia',
                'category'       => 'venue',
                'amount'         => 20_000_000,
                'due_date'       => now()->subDays(35)->toDateString(),
                'status'         => 'paid',
                'paid_at'        => now()->subDays(33),
                'payment_method' => 'Transfer BCA',
                'notes'          => 'Pembayaran booking venue.',
                'sort_order'     => 1,
            ],
            [
                'title'          => 'DP Catering',
                'vendor_name'    => 'Sriwijaya Catering',
                'category'       => 'catering',
                'amount'         => 35_000_000,
                'due_date'       => now()->subDays(14)->toDateString(),
                'status'         => 'paid',
                'paid_at'        => now()->subDays(12),
                'payment_method' => 'Transfer Mandiri',
                'notes'          => 'DP catering 500 pax.',
                'sort_order'     => 2,
            ],
            [
                'title'      => 'Pelunasan Dekorasi',
                'vendor_name' => 'Makna Decoration',
                'category'   => 'decoration',
                'amount'     => 25_000_000,
                'due_date'   => now()->addDays(10)->toDateString(),
                'status'     => 'pending',
                'notes'      => 'Pelunasan dekorasi pelaminan dan area resepsi.',
                'sort_order' => 3,
            ],
            [
                'title'      => 'Pelunasan Foto & Video',
                'vendor_name' => 'Makna Creative Studio',
                'category'   => 'photo_video',
                'amount'     => 18_000_000,
                'due_date'   => now()->addDays(25)->toDateString(),
                'status'     => 'pending',
                'notes'      => 'Dokumentasi akad dan resepsi.',
                'sort_order' => 4,
            ],
            [
                'title'      => 'Makeup & Busana Keluarga',
                'vendor_name' => 'Putri Bridal',
                'category'   => 'makeup',
                'amount'     => 12_000_000,
                'due_date'   => now()->addDays(35)->toDateString(),
                'status'     => 'pending',
                'notes'      => 'Makeup pengantin dan keluarga inti.',
                'sort_order' => 5,
            ],
            [
                'title'      => 'Transportasi Keluarga',
                'vendor_name' => 'Palembang Wedding Transport',
                'category'   => 'transport',
                'amount'     => 5_000_000,
                'due_date'   => now()->subDays(3)->toDateString(),
                'status'     => 'overdue',
                'notes'      => 'Transport keluarga besar dan tamu VIP.',
                'sort_order' => 6,
            ],
        ];

        foreach ($schedules as $schedule) {
            WeddingPaymentSchedule::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'title'   => $schedule['title'],
                ],
                array_merge($schedule, [
                    'user_id' => $user->id,
                ]),
            );
        }
    }
}
