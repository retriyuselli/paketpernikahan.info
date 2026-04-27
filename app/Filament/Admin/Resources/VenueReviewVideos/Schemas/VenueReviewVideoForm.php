<?php

namespace App\Filament\Admin\Resources\VenueReviewVideos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class VenueReviewVideoForm
{
    public static function schema(): array
    {
        return [
            Section::make('Detail Video')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul Venue')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('subtitle')
                        ->label('Subtitle')
                        ->default('Introducing')
                        ->maxLength(255),
                    TextInput::make('location')
                        ->label('Lokasi')
                        ->maxLength(255)
                        ->placeholder('at Aston Palembang'),
                    TextInput::make('video_url')
                        ->label('URL Video (YouTube)')
                        ->url()
                        ->maxLength(500)
                        ->placeholder('https://youtu.be/...'),
                ])->columns(2),

            Section::make('Thumbnail & Status')
                ->schema([
                    FileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->image()
                        ->disk('public')
                        ->directory('venue-reviews')
                        ->columnSpanFull(),
                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ];
    }
}
