<?php

namespace App\Filament\Admin\Resources\PartnerLogos\Pages;

use App\Filament\Admin\Resources\PartnerLogos\PartnerLogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartnerLogos extends ListRecords
{
    protected static string $resource = PartnerLogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
