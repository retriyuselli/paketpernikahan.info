<?php

namespace App\Filament\Admin\Resources\VendorGalleries\Pages;

use App\Filament\Admin\Resources\VendorGalleries\VendorGalleryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorGalleries extends ListRecords
{
    protected static string $resource = VendorGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
