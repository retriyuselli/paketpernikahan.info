<?php

namespace App\Filament\Admin\Resources\CategoryVendors\Pages;

use App\Filament\Admin\Resources\CategoryVendors\CategoryVendorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryVendors extends ListRecords
{
    protected static string $resource = CategoryVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
