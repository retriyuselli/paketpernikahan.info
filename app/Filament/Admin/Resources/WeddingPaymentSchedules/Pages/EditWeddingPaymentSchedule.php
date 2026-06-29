<?php

namespace App\Filament\Admin\Resources\WeddingPaymentSchedules\Pages;

use App\Filament\Admin\Resources\WeddingPaymentSchedules\WeddingPaymentScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeddingPaymentSchedule extends EditRecord
{
    protected static string $resource = WeddingPaymentScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
