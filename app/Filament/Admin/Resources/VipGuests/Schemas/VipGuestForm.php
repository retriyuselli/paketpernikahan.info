<?php

namespace App\Filament\Admin\Resources\VipGuests\Schemas;

use App\Models\VipGuest;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class VipGuestForm
{
    public static function schema(): array
    {
        return [
            Section::make('Data Tamu VIP')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nama Tamu')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('jabatan')
                        ->label('Jabatan')
                        ->placeholder('contoh: Walikota, Direktur Utama')
                        ->maxLength(150)
                        ->nullable(),

                    TextInput::make('instansi')
                        ->label('Instansi / Perusahaan')
                        ->placeholder('contoh: Pemkot Palembang, PT. Maju Jaya')
                        ->maxLength(150)
                        ->nullable(),

                    TextInput::make('phone')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->maxLength(30)
                        ->nullable(),

                    Select::make('kategori')
                        ->label('Kategori')
                        ->options(VipGuest::$kategoriOptions)
                        ->default('teman')
                        ->required(),

                    Select::make('rsvp_status')
                        ->label('Status RSVP')
                        ->options(VipGuest::$rsvpOptions)
                        ->default('menunggu')
                        ->required(),

                    Textarea::make('catatan')
                        ->label('Catatan')
                        ->rows(2)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
