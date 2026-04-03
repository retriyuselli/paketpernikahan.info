<?php

namespace App\Filament\Admin\Resources\PaketGalleries\Pages;

use App\Filament\Admin\Resources\PaketGalleries\PaketGalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaketGallery extends EditRecord
{
    protected static string $resource = PaketGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

