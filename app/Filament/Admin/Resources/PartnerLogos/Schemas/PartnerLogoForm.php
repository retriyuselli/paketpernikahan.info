<?php

namespace App\Filament\Admin\Resources\PartnerLogos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class PartnerLogoForm
{
    public static function schema(): array
    {
        return [
            Section::make('Logo')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama (opsional)')
                        ->maxLength(255),

                    TextInput::make('link_url')
                        ->label('URL (opsional)')
                        ->url()
                        ->maxLength(500)
                        ->placeholder('https://...'),

                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->disk('public')
                        ->directory('partner-logos')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Pengaturan')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ];
    }
}
