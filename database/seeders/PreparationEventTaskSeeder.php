<?php

namespace Database\Seeders;

use App\Models\CustomerPreparationTask;
use App\Models\User;
use App\Models\WeddingEvent;
use Illuminate\Database\Seeder;

class PreparationEventTaskSeeder extends Seeder
{
    private array $templates = [
        'lamaran' => [
            ['label' => 'Persiapan Umum', 'title' => 'Tentukan tanggal dan lokasi lamaran',       'status' => 'done',    'due_offset' => -60],
            ['label' => 'Persiapan Umum', 'title' => 'Konfirmasi kehadiran keluarga kedua pihak', 'status' => 'done',    'due_offset' => -30],
            ['label' => 'Seserahan',      'title' => 'Siapkan seserahan / hantaran',              'status' => 'done',    'due_offset' => -14],
            ['label' => 'Katering',       'title' => 'Pesan katering untuk acara lamaran',         'status' => 'pending', 'due_offset' => -14],
            ['label' => 'Busana',         'title' => 'Siapkan busana lamaran',                     'status' => 'pending', 'due_offset' => -7],
            ['label' => 'Dekorasi',       'title' => 'Dekorasi tempat lamaran',                    'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Persiapan Umum', 'title' => 'Tentukan MC / pembawa acara',                'status' => 'todo',    'due_offset' => -7],
            ['label' => 'Dokumentasi',    'title' => 'Booking fotografer lamaran',                 'status' => 'todo',    'due_offset' => -14],
            ['label' => 'Dokumen',        'title' => 'Siapkan dokumen & surat pernyataan',         'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Persiapan Umum', 'title' => 'Gladi resik susunan acara lamaran',          'status' => 'todo',    'due_offset' => -1],
        ],

        'pengajian' => [
            ['label' => 'Persiapan Umum', 'title' => 'Konfirmasi jadwal dan lokasi pengajian',   'status' => 'done',    'due_offset' => -30],
            ['label' => 'Pemimpin Acara', 'title' => 'Undang ustadz / kyai',                      'status' => 'done',    'due_offset' => -21],
            ['label' => 'Persiapan Umum', 'title' => 'Siapkan buku yasin / bacaan Al-Quran',     'status' => 'done',    'due_offset' => -14],
            ['label' => 'Persiapan Umum', 'title' => 'Konfirmasi jumlah tamu pengajian',          'status' => 'done',    'due_offset' => -14],
            ['label' => 'Konsumsi',       'title' => 'Pesan konsumsi untuk tamu pengajian',       'status' => 'pending', 'due_offset' => -7],
            ['label' => 'Dekorasi',       'title' => 'Siapkan dekorasi ruangan pengajian',        'status' => 'pending', 'due_offset' => -5],
            ['label' => 'Sound System',   'title' => 'Sewa / pinjam sound system',                'status' => 'todo',    'due_offset' => -7],
            ['label' => 'Busana',         'title' => 'Siapkan busana pengajian (islami)',          'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Dokumentasi',    'title' => 'Booking fotografer pengajian',               'status' => 'todo',    'due_offset' => -14],
            ['label' => 'Persiapan Umum', 'title' => 'Susun rundown acara pengajian',             'status' => 'todo',    'due_offset' => -3],
        ],

        'akad' => [
            ['label' => 'Dokumen Nikah',  'title' => 'Urus surat nikah di KUA / penghulu',        'status' => 'done',    'due_offset' => -90],
            ['label' => 'Mahar & Cincin', 'title' => 'Siapkan mahar (mas kawin)',                  'status' => 'done',    'due_offset' => -30],
            ['label' => 'Penghulu & Wali','title' => 'Konfirmasi penghulu / wali nikah',           'status' => 'done',    'due_offset' => -30],
            ['label' => 'Penghulu & Wali','title' => 'Pilih dan konfirmasi dua saksi akad',        'status' => 'done',    'due_offset' => -21],
            ['label' => 'Prosesi Akad',   'title' => 'Latihan ijab kabul',                         'status' => 'pending', 'due_offset' => -7],
            ['label' => 'Mahar & Cincin', 'title' => 'Siapkan cincin pernikahan',                  'status' => 'pending', 'due_offset' => -14],
            ['label' => 'Busana & MUA',   'title' => 'Booking makeup & busana akad',               'status' => 'pending', 'due_offset' => -14],
            ['label' => 'Prosesi Akad',   'title' => 'Konfirmasi lokasi dan waktu akad nikah',     'status' => 'done',    'due_offset' => -60],
            ['label' => 'Prosesi Akad',   'title' => 'Siapkan Al-Quran dan perlengkapan akad',    'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Prosesi Akad',   'title' => 'Gladi resik prosesi akad',                   'status' => 'todo',    'due_offset' => -1],
            ['label' => 'Dokumentasi',    'title' => 'Fotografi & videografi akad dikonfirmasi',   'status' => 'todo',    'due_offset' => -14],
        ],

        'resepsi' => [
            ['label' => 'Venue',       'title' => 'Booking dan tanda tangan kontrak venue',    'status' => 'done',    'due_offset' => -120],
            ['label' => 'Dekorasi',    'title' => 'Pilih tema dan konsep dekorasi',            'status' => 'done',    'due_offset' => -90],
            ['label' => 'Katering',    'title' => 'Kontrak katering dan food tasting',         'status' => 'done',    'due_offset' => -60],
            ['label' => 'Undangan',    'title' => 'Desain dan cetak undangan',                 'status' => 'done',    'due_offset' => -45],
            ['label' => 'Undangan',    'title' => 'Kirim undangan ke tamu',                   'status' => 'done',    'due_offset' => -30],
            ['label' => 'Dokumentasi', 'title' => 'Konfirmasi fotografer & videografer',       'status' => 'done',    'due_offset' => -60],
            ['label' => 'Busana & MUA','title' => 'Fitting dan ambil busana resepsi',          'status' => 'pending', 'due_offset' => -14],
            ['label' => 'Souvenir',    'title' => 'Pesan dan distribusi souvenir tamu',        'status' => 'pending', 'due_offset' => -14],
            ['label' => 'Acara & MC',  'title' => 'Konfirmasi MC dan susunan acara',           'status' => 'pending', 'due_offset' => -7],
            ['label' => 'Acara & MC',  'title' => 'Konfirmasi hiburan / live music',           'status' => 'todo',    'due_offset' => -21],
            ['label' => 'Venue',       'title' => 'Cek sound system dan pencahayaan venue',    'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Katering',    'title' => 'Konfirmasi porsi katering final',            'status' => 'todo',    'due_offset' => -7],
            ['label' => 'Venue',       'title' => 'Koordinasi parkir dan keamanan',            'status' => 'todo',    'due_offset' => -3],
            ['label' => 'Acara & MC',  'title' => 'Gladi resik prosesi resepsi',               'status' => 'todo',    'due_offset' => -1],
            ['label' => 'Pembayaran',  'title' => 'Pelunasan semua vendor',                    'status' => 'todo',    'due_offset' => -3],
        ],
    ];

    public function run(): void
    {
        $users = User::whereHas('weddingEvents')->get();

        if ($users->isEmpty()) {
            $this->command->warn('PreparationEventTaskSeeder: tidak ada user dengan wedding events, skip.');
            return;
        }

        foreach ($users as $user) {
            $this->seedEventTasks($user);
        }

        $this->command->info("PreparationEventTaskSeeder: selesai untuk {$users->count()} user.");
    }

    private function seedEventTasks(User $user): void
    {
        $events = WeddingEvent::where('user_id', $user->id)->get();

        foreach ($events as $event) {
            $template = $this->templates[$event->jenis_acara] ?? [];

            if (empty($template)) {
                continue;
            }

            CustomerPreparationTask::where('wedding_event_id', $event->id)->delete();

            foreach ($template as $sort => $item) {
                $dueDate = $event->tgl_acara
                    ? $event->tgl_acara->copy()->addDays($item['due_offset'])->toDateString()
                    : null;

                CustomerPreparationTask::create([
                    'wedding_event_id' => $event->id,
                    'section_id'       => null,
                    'label'            => $item['label'],
                    'user_id'          => $user->id,
                    'title'            => $item['title'],
                    'status'           => $item['status'],
                    'due_date'         => $dueDate,
                    'sort_order'       => $sort + 1,
                ]);
            }

            $count = count($template);
            $this->command->line("  ✓ {$user->name} → {$event->jenis_acara} ({$event->tgl_acara?->toDateString()}) — {$count} tasks");
        }
    }
}
