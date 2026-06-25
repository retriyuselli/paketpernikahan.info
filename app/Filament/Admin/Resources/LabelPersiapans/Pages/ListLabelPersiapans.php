<?php

namespace App\Filament\Admin\Resources\LabelPersiapans\Pages;

use App\Filament\Admin\Resources\LabelPersiapans\LabelPersiapanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabelPersiapans extends ListRecords
{
    protected static string $resource = LabelPersiapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
