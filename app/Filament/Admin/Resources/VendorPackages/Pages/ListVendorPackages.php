<?php

namespace App\Filament\Admin\Resources\VendorPackages\Pages;

use App\Filament\Admin\Resources\VendorPackages\VendorPackageResource;
use App\Filament\Imports\VendorPackageImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPackages extends ListRecords
{
    protected static string $resource = VendorPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(VendorPackageImporter::class)
                ->label('Import CSV'),
            CreateAction::make(),
        ];
    }
}
