<?php

namespace App\Observers;

use App\Models\CustomerPreparationTask;
use App\Models\PreparationTaskTemplate;
use App\Models\WeddingEvent;

class WeddingEventObserver
{
    /**
     * Saat WeddingEvent baru dibuat, otomatis salin semua template tugas
     * yang sesuai dengan jenis_acara ke customer_preparation_tasks.
     * Hanya berjalan jika event belum memiliki task (idempoten).
     */
    public function created(WeddingEvent $event): void
    {
        $alreadyHasTasks = CustomerPreparationTask::where('wedding_event_id', $event->id)->exists();

        if ($alreadyHasTasks) {
            return;
        }

        $templates = PreparationTaskTemplate::where('jenis_acara', $event->jenis_acara)
            ->orderBy('sort_order')
            ->get();

        if ($templates->isEmpty()) {
            return;
        }

        $now  = now();
        $rows = $templates->values()->map(fn ($tpl, $i) => [
            'wedding_event_id' => $event->id,
            'user_id'          => $event->user_id,
            'section_id'       => null,
            'label'            => $tpl->label,
            'title'            => $tpl->title,
            'status'           => 'todo',
            'due_date'         => null,
            'sort_order'       => $i + 1,
            'created_at'       => $now,
            'updated_at'       => $now,
        ])->all();

        CustomerPreparationTask::insert($rows);
    }
}
