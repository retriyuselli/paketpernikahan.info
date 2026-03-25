<?php

namespace App\Filament\Admin\Resources\VendorPackages\Schemas;

use App\Models\Vendor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;

class VendorPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Paket')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->options(Vendor::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('price')
                            ->label('Harga (tampil)')
                            ->required()
                            ->placeholder('Rp 45.000.000'),
                        TextInput::make('discount')
                            ->label('Potongan Harga')
                            ->prefix('Rp. ')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state))
                            ->placeholder('0')
                            ->helperText('Isi jika ada potongan harga khusus'),
                        TextInput::make('max_guests')
                            ->label('Kapasitas Tamu')
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Tampilan Kartu')
                    ->columns(2)
                    ->schema([
                        ColorPicker::make('card_color')
                            ->label('Warna Kartu')
                            ->required()
                            ->default('#C8D5B9'),
                        ColorPicker::make('card_text_color')
                            ->label('Warna Teks')
                            ->required()
                            ->default('#444444'),
                    ]),

                Section::make('Fasilitas Termasuk')
                    ->schema([
                        Repeater::make('items')
                            ->label('Item Fasilitas')
                            ->simple(
                                TextInput::make('item')->required()
                            )
                            ->addActionLabel('Tambah Fasilitas')
                            ->reorderable()
                            ->required(),
                    ]),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
