<?php

namespace App\Filament\Admin\Resources\WeddingIncomingPayments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WeddingIncomingPaymentForm
{
    public static function schema(): array
    {
        return [
            Section::make('Sumber Dana')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('bank_name')
                        ->label('Sumber Dana')
                        ->placeholder('Contoh: Tabungan Pribadi, Orang Tua, Simpanan Bersama')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('amount')
                        ->label('Nominal')
                        ->prefix('Rp')
                        ->numeric()
                        ->minValue(1)
                        ->required(),

                    DatePicker::make('transfer_date')
                        ->label('Tanggal Dana Masuk')
                        ->required(),

                    TextInput::make('sender_name')
                        ->label('Dari / Kontributor')
                        ->placeholder('Contoh: Varisha Aaliya, Orang Tua Mempelai Wanita')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('reference_number')
                        ->label('Referensi / Catatan Bukti')
                        ->maxLength(100)
                        ->nullable(),

                    FileUpload::make('proof_url')
                        ->label('Bukti Dana Masuk')
                        ->disk('public')
                        ->directory('wedding-incoming-payment-proofs')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->nullable()
                        ->columnSpanFull(),

                    TextInput::make('description')
                        ->label('Alokasi / Keterangan')
                        ->maxLength(300)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Status Konfirmasi')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'   => 'Menunggu',
                            'confirmed' => 'Dikonfirmasi',
                            'rejected'  => 'Ditolak',
                        ])
                        ->default('pending')
                        ->required()
                        ->live(),

                    DateTimePicker::make('confirmed_at')
                        ->label('Waktu Konfirmasi')
                        ->nullable(),

                    TextInput::make('confirmed_by')
                        ->label('Dikonfirmasi Oleh')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('rejection_reason')
                        ->label('Alasan Ditolak')
                        ->maxLength(255)
                        ->nullable(),

                    Textarea::make('notes')
                        ->label('Catatan Internal')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
