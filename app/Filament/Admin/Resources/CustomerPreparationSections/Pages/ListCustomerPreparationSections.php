<?php

namespace App\Filament\Admin\Resources\CustomerPreparationSections\Pages;

use App\Filament\Admin\Resources\CustomerPreparationSections\CustomerPreparationSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPreparationSections extends ListRecords
{
    protected static string $resource = CustomerPreparationSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
