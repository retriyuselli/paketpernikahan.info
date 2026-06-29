<?php

namespace App\Filament\Admin\Resources\WeddingBudgets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WeddingBudgetForm
{
    public static function schema(): array
    {
        return [
            Section::make('Budget Pernikahan')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->unique(ignoreRecord: true),

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
                        ->label('Catatan')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
