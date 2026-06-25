<?php

namespace App\Filament\Admin\Resources\PreparationTaskTemplates\Pages;

use App\Filament\Admin\Resources\PreparationTaskTemplates\PreparationTaskTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreparationTaskTemplates extends ListRecords
{
    protected static string $resource = PreparationTaskTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
