<?php

namespace App\Filament\Admin\Resources\CategoryVendors\Pages;

use App\Filament\Admin\Resources\CategoryVendors\CategoryVendorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryVendor extends EditRecord
{
    protected static string $resource = CategoryVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
