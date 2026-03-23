<?php

namespace App\Filament\Admin\Resources\VendorPackages\Pages;

use App\Filament\Admin\Resources\VendorPackages\VendorPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorPackage extends EditRecord
{
    protected static string $resource = VendorPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
