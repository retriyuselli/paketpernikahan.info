<?php

namespace App\Filament\Admin\Resources\VendorReviews\Pages;

use App\Filament\Admin\Resources\VendorReviews\VendorReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorReviews extends ListRecords
{
    protected static string $resource = VendorReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
