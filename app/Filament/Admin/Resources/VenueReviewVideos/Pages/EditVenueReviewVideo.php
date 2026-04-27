<?php

namespace App\Filament\Admin\Resources\VenueReviewVideos\Pages;

use App\Filament\Admin\Resources\VenueReviewVideos\VenueReviewVideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenueReviewVideo extends EditRecord
{
    protected static string $resource = VenueReviewVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
