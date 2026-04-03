<?php

namespace App\Filament\Admin\Resources\PaketGalleries\Schemas;

use App\Models\VendorPackage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaketGalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto Paket')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_package_id')
                            ->label('Paket')
                            ->options(VendorPackage::orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        TextInput::make('caption')
                            ->label('Keterangan')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        FileUpload::make('image_video')
                                ->label('File Video Paket')
                                ->disk('public')
                                ->directory('package-galleries')
                                ->columnSpanFull(),
                        TextInput::make('video_url')
                            ->label('Link Video YouTube')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
