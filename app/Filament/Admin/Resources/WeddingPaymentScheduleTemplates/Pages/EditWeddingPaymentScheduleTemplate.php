<?php

namespace App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\Pages;

use App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\WeddingPaymentScheduleTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeddingPaymentScheduleTemplate extends EditRecord
{
    protected static string $resource = WeddingPaymentScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
