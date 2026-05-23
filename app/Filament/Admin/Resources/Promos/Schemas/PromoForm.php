<?php

namespace App\Filament\Admin\Resources\Promos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class PromoForm
{
    public static function schema(): array
    {
        return [
            Section::make('Kode & Diskon')
                ->schema([
                    TextInput::make('code')
                        ->label('Kode Promo')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('code', Str::upper($state)))
                        ->placeholder('Contoh: WEDDING10'),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Select::make('type')
                        ->label('Tipe Diskon')
                        ->options([
                            'fixed'      => 'Nominal Tetap (Rp)',
                            'percentage' => 'Persentase (%)',
                        ])
                        ->required()
                        ->live(),

                    TextInput::make('value')
                        ->label(fn ($get) => $get('type') === 'percentage' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn ($get) => $get('type') === 'percentage' ? 100 : null)
                        ->prefix(fn ($get) => $get('type') !== 'percentage' ? 'Rp' : null)
                        ->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : null)
                        ->mask(fn ($get) => $get('type') !== 'percentage' ? RawJs::make('$money($input)') : null)
                        ->stripCharacters(',')
                        ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state)),

                    TextInput::make('max_discount')
                        ->label('Maksimal Potongan (Rp)')
                        ->prefix('Rp')
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric()
                        ->nullable()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) preg_replace('/[^\d]/', '', (string) $state) : null)
                        ->visible(fn ($get) => $get('type') === 'percentage')
                        ->helperText('Batas atas nominal diskon. Kosongkan = tidak ada batas.'),

                    TextInput::make('min_amount')
                        ->label('Minimal Total Booking (Rp)')
                        ->prefix('Rp')
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric()
                        ->default(0)
                        ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state)),
                ])->columns(2),

            Section::make('Batas Pemakaian & Masa Berlaku')
                ->schema([
                    TextInput::make('max_uses')
                        ->label('Batas Pemakaian')
                        ->numeric()
                        ->nullable()
                        ->helperText('Kosongkan = unlimited.'),

                    TextInput::make('uses_count')
                        ->label('Sudah Dipakai')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated(false),

                    DateTimePicker::make('valid_from')
                        ->label('Berlaku Mulai')
                        ->nullable(),

                    DateTimePicker::make('valid_until')
                        ->label('Berlaku Hingga')
                        ->nullable(),

                    Select::make('vendorPackages')
                        ->label('Khusus Paket')
                        ->relationship(
                            name: 'vendorPackages',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->with('vendor'),
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->name . ' — ' . ($record->vendor?->name ?? ''))
                        ->multiple()
                        ->searchable()
                        ->helperText('Kosongkan = berlaku untuk semua paket.')
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ];
    }
}
