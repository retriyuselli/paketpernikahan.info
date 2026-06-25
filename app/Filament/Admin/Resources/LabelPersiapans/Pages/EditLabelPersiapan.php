<?php

namespace App\Filament\Admin\Resources\LabelPersiapans\Pages;

use App\Filament\Admin\Resources\LabelPersiapans\LabelPersiapanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLabelPersiapan extends EditRecord
{
    protected static string $resource = LabelPersiapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
