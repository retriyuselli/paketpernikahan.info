<?php

namespace App\Filament\Admin\Resources\HomeAds\Pages;

use App\Filament\Admin\Resources\HomeAds\HomeAdResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeAd extends EditRecord
{
    protected static string $resource = HomeAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
