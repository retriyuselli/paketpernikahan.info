<?php

namespace App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\Pages;

use App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\WeddingPaymentScheduleTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeddingPaymentScheduleTemplates extends ListRecords
{
    protected static string $resource = WeddingPaymentScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
