<?php

namespace App\Filament\Admin\Resources\CustomerNotifications\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class CustomerNotificationForm
{
    public static function schema(): array
    {
        return [
            Section::make('Notifikasi')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('group')
                        ->label('Grup')
                        ->options([
                            'Pembayaran' => 'Pembayaran',
                            'Persiapan'  => 'Persiapan',
                            'Booking'    => 'Booking',
                            'Promo'      => 'Promo',
                            'Sistem'     => 'Sistem',
                        ])
                        ->nullable(),

                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(150)
                        ->columnSpanFull(),

                    Textarea::make('message')
                        ->label('Pesan')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),

                    TextInput::make('icon')
                        ->label('Icon (SF Symbol)')
                        ->placeholder('contoh: bell.fill, creditcard.fill')
                        ->maxLength(80)
                        ->nullable(),

                    TextInput::make('destination')
                        ->label('Tujuan (Deep Link)')
                        ->placeholder('contoh: payment, preparation, booking')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('tint')
                        ->label('Warna (Hex)')
                        ->placeholder('#F5527F')
                        ->maxLength(20)
                        ->nullable(),

                    Toggle::make('is_unread')
                        ->label('Belum Dibaca')
                        ->default(true),
                ])->columns(2),
        ];
    }
}
