<?php

namespace App\Filament\Admin\Resources\VenueReviewVideos\Pages;

use App\Filament\Admin\Resources\VenueReviewVideos\VenueReviewVideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVenueReviewVideos extends ListRecords
{
    protected static string $resource = VenueReviewVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
