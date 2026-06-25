<?php

namespace App\Filament\Admin\Resources\PreparationTaskTemplates\Pages;

use App\Filament\Admin\Resources\PreparationTaskTemplates\PreparationTaskTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreparationTaskTemplate extends EditRecord
{
    protected static string $resource = PreparationTaskTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
