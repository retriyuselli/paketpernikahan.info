<?php

namespace App\Filament\Admin\Resources\PaketGalleries\Pages;

use App\Filament\Admin\Resources\PaketGalleries\PaketGalleryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaketGalleries extends ListRecords
{
    protected static string $resource = PaketGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

