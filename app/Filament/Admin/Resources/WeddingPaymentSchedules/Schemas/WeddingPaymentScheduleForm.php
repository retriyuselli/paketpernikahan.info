<?php

namespace App\Filament\Admin\Resources\WeddingPaymentSchedules\Schemas;

use App\Models\CustomerPaymentMethod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;

class WeddingPaymentScheduleForm
{
    public static function schema(): array
    {
        return [
            Section::make('Jadwal Pembayaran')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('vendor_name')
                        ->label('Vendor')
                        ->required()
                        ->maxLength(200),

                    Select::make('category')
                        ->label('Kategori')
                        ->options([
                            'venue'         => 'Venue',
                            'catering'      => 'Catering',
                            'decoration'    => 'Dekorasi',
                            'photo_video'   => 'Foto & Video',
                            'entertainment' => 'Entertainment',
                            'makeup'        => 'Makeup & Busana',
                            'transport'     => 'Transportasi',
                            'wo'            => 'Wedding Organizer',
                            'other'         => 'Lainnya',
                        ])
                        ->default('other')
                        ->required(),

                    TextInput::make('amount')
                        ->label('Nominal')
                        ->prefix('Rp')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    DatePicker::make('due_date')
                        ->label('Jatuh Tempo')
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid'    => 'Lunas',
                            'overdue' => 'Lewat Tempo',
                        ])
                        ->default('pending')
                        ->required(),

                    DateTimePicker::make('paid_at')
                        ->label('Tanggal Bayar')
                        ->nullable(),

                    Select::make('customer_payment_method_id')
                        ->label('Metode Pembayaran')
                        ->options(fn (Get $get): array => CustomerPaymentMethod::query()
                            ->where('user_id', $get('user_id'))
                            ->get()
                            ->mapWithKeys(fn (CustomerPaymentMethod $pm): array => [
                                $pm->id => "{$pm->name} – {$pm->account_number} ({$pm->account_name})",
                            ])
                            ->all()
                        )
                        ->reactive()
                        ->searchable()
                        ->nullable(),

                    FileUpload::make('proof_url')
                        ->label('Bukti Bayar')
                        ->disk('public')
                        ->directory('wedding-payment-proofs')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->nullable(),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
