<?php

namespace App\Filament\Admin\Resources\VendorPackages\Pages;

use App\Filament\Admin\Resources\VendorPackages\VendorPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPackages extends ListRecords
{
    protected static string $resource = VendorPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
