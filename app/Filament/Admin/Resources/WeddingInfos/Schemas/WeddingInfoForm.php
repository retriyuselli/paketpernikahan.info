<?php

namespace App\Filament\Admin\Resources\WeddingInfos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WeddingInfoForm
{
    public static function schema(): array
    {
        return [
            Section::make('Info Pernikahan')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('groom_name')
                        ->label('Nama Pengantin Pria')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('bride_name')
                        ->label('Nama Pengantin Wanita')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('budaya')
                        ->label('Budaya / Adat')
                        ->maxLength(100)
                        ->nullable(),

                    TagsInput::make('songlist')
                        ->label('Songlist')
                        ->separator(',')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Budget')
                ->relationship('budget')
                ->schema([
                    TextInput::make('total_budget')
                        ->label('Total Budget')
                        ->numeric()
                        ->minValue(0)
                        ->prefix(fn ($get) => match ($get('currency') ?? 'IDR') {
                            'IDR' => 'Rp',
                            'USD' => '$',
                            'SGD' => 'S$',
                            'MYR' => 'RM',
                            default => $get('currency') ?? 'IDR',
                        })
                        ->default(0)
                        ->required(),

                    Select::make('currency')
                        ->label('Currency')
                        ->options([
                            'IDR' => 'IDR - Rupiah Indonesia',
                            'USD' => 'USD - US Dollar',
                            'SGD' => 'SGD - Singapore Dollar',
                            'MYR' => 'MYR - Malaysian Ringgit',
                        ])
                        ->default('IDR')
                        ->required()
                        ->live(),

                    Textarea::make('notes')
                        ->label('Catatan Budget')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
