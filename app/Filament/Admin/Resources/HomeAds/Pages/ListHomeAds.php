<?php

namespace App\Filament\Admin\Resources\HomeAds\Pages;

use App\Filament\Admin\Resources\HomeAds\HomeAdResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeAds extends ListRecords
{
    protected static string $resource = HomeAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
