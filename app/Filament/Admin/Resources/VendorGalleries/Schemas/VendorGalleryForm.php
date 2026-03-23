<?php

namespace App\Filament\Admin\Resources\VendorGalleries\Schemas;

use App\Models\Vendor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorGalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto Vendor')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->options(Vendor::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        FileUpload::make('image_path')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('galleries')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('caption')
                            ->label('Keterangan')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('video_url')
                            ->label('Link Video YouTube')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->columnSpanFull(),
                        Toggle::make('is_cover')
                            ->label('Jadikan Cover'),
                    ]),
            ]);
    }
}
