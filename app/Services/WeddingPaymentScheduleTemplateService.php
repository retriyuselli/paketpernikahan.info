<?php

namespace App\Services;

use App\Models\WeddingEvent;
use App\Models\WeddingPaymentSchedule;
use App\Models\WeddingPaymentScheduleTemplate;
use Carbon\CarbonInterface;

class WeddingPaymentScheduleTemplateService
{
    public function createSchedulesForEvent(WeddingEvent $event): int
    {
        $templates = WeddingPaymentScheduleTemplate::where('jenis_acara', $event->jenis_acara)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($templates->isEmpty()) {
            return 0;
        }

        $created = 0;

        foreach ($templates as $template) {
            $schedule = WeddingPaymentSchedule::firstOrCreate(
                [
                    'user_id'            => $event->user_id,
                    'wedding_event_id'   => $event->id,
                    'source_template_id' => $template->id,
                ],
                [
                    'title'          => $template->title,
                    'vendor_name'    => $template->vendor_name,
                    'category'       => $template->category,
                    'amount'         => $template->amount,
                    'due_date'       => $this->resolveDueDate($event, $template->due_days_before_event),
                    'status'         => 'pending',
                    'payment_method' => null,
                    'proof_url'      => null,
                    'notes'          => $template->notes,
                    'sort_order'     => $template->sort_order,
                ],
            );

            if ($schedule->wasRecentlyCreated) {
                $created++;
            }
        }

        return $created;
    }

    private function resolveDueDate(WeddingEvent $event, int $daysBeforeEvent): CarbonInterface
    {
        if ($event->tgl_acara) {
            return $event->tgl_acara->copy()->subDays($daysBeforeEvent);
        }

        return now()->addDays(max(1, $daysBeforeEvent));
    }
}
