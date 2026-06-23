<?php

namespace App\Filament\Admin\Resources\WeddingInfos\Pages;

use App\Filament\Admin\Resources\WeddingInfos\WeddingInfoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeddingInfo extends EditRecord
{
    protected static string $resource = WeddingInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
