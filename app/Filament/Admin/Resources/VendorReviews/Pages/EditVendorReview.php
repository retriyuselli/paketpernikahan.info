<?php

namespace App\Filament\Admin\Resources\VendorReviews\Pages;

use App\Filament\Admin\Resources\VendorReviews\VendorReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorReview extends EditRecord
{
    protected static string $resource = VendorReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
