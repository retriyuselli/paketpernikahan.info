<?php

namespace App\Filament\Admin\Resources\CategoryVendors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryVendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $set('slug', Str::slug($state))
                            ),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('icon')
                            ->label('Icon (Heroicon)')
                            ->placeholder('e.g. building-office')
                            ->helperText('Nama heroicon tanpa prefix, e.g. building-office, sparkles, home'),
                        Select::make('color')
                            ->label('Warna Badge')
                            ->required()
                            ->default('gray')
                            ->options([
                                'gray'    => 'Gray',
                                'warning' => 'Warning (kuning)',
                                'info'    => 'Info (biru)',
                                'success' => 'Success (hijau)',
                                'danger'  => 'Danger (merah)',
                                'primary' => 'Primary',
                            ]),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
