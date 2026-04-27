<?php

namespace App\Filament\Admin\Resources\VenueReviewVideos;

use App\Filament\Admin\Resources\VenueReviewVideos\Pages\CreateVenueReviewVideo;
use App\Filament\Admin\Resources\VenueReviewVideos\Pages\EditVenueReviewVideo;
use App\Filament\Admin\Resources\VenueReviewVideos\Pages\ListVenueReviewVideos;
use App\Models\VenueReviewVideo;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class VenueReviewVideoResource extends Resource
{
    protected static ?string $model = VenueReviewVideo::class;

    protected static ?string $navigationLabel = 'Venue Review Videos';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-video-camera';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Frontend';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\VenueReviewVideoForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\VenueReviewVideosTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVenueReviewVideos::route('/'),
            'create' => CreateVenueReviewVideo::route('/create'),
            'edit'   => EditVenueReviewVideo::route('/{record}/edit'),
        ];
    }
}
