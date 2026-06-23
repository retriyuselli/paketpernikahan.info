<?php

namespace App\Filament\Admin\Resources\CustomerPaymentMethods\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class CustomerPaymentMethodForm
{
    public static function schema(): array
    {
        return [
            Section::make('Metode Pembayaran')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'bank'    => 'Bank Transfer',
                            'ewallet' => 'E-Wallet',
                        ])
                        ->required()
                        ->default('bank'),

                    TextInput::make('name')
                        ->label('Nama Bank / E-Wallet')
                        ->placeholder('contoh: BCA, GoPay')
                        ->required()
                        ->maxLength(80),

                    TextInput::make('logo_icon')
                        ->label('Icon (SF Symbol)')
                        ->placeholder('contoh: creditcard.fill')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('account_number')
                        ->label('Nomor Rekening / Akun')
                        ->required()
                        ->maxLength(60),

                    TextInput::make('account_name')
                        ->label('Nama Pemilik Akun')
                        ->required()
                        ->maxLength(100),

                    Toggle::make('is_primary')
                        ->label('Jadikan Utama')
                        ->default(false),
                ])->columns(2),
        ];
    }
}
