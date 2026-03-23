<?php

namespace App\Filament\Admin\Resources\VendorGalleries\Pages;

use App\Filament\Admin\Resources\VendorGalleries\VendorGalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorGallery extends EditRecord
{
    protected static string $resource = VendorGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
