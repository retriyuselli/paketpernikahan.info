<?php

namespace App\Filament\Admin\Resources\CustomerPreparationSections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class CustomerPreparationSectionForm
{
    public static function schema(): array
    {
        return [
            Section::make('Seksi Persiapan')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('title')
                        ->label('Judul Seksi')
                        ->placeholder('contoh: Venue & Dekorasi')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('icon')
                        ->label('Icon (SF Symbol)')
                        ->placeholder('contoh: building.2.fill')
                        ->maxLength(80)
                        ->nullable(),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ];
    }
}
