<?php

namespace App\Filament\Admin\Resources\Promos\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $title = 'Riwayat Penggunaan';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->bookings()->count();

        return $count > 0 ? (string) $count : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pemesan')
                ->columns(2)
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Nama'),

                    TextEntry::make('user.email')
                        ->label('Email'),

                    TextEntry::make('phone')
                        ->label('WhatsApp'),
                ]),

            Section::make('Detail Booking')
                ->columns(2)
                ->schema([
                    TextEntry::make('vendorPackage.name')
                        ->label('Paket')
                        ->placeholder('—'),

                    TextEntry::make('vendor.name')
                        ->label('Vendor')
                        ->placeholder('—'),

                    TextEntry::make('event_date')
                        ->label('Tanggal Acara')
                        ->date('d M Y'),

                    TextEntry::make('status')
                        ->label('Status Booking')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'confirmed' => 'success',
                            'done'      => 'success',
                            'cancelled' => 'danger',
                            'contacted' => 'info',
                            default     => 'warning',
                        }),
                ]),

            Section::make('Rincian Harga')
                ->columns(2)
                ->schema([
                    TextEntry::make('promo_discount')
                        ->label('Potongan Promo')
                        ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '—')
                        ->color('success'),

                    TextEntry::make('agreed_total')
                        ->label('Total Setelah Promo')
                        ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '—'),

                    TextEntry::make('payment_status')
                        ->label('Status Pembayaran')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'paid'       => 'success',
                            'dp_paid'    => 'info',
                            'dp_waiting' => 'warning',
                            'unpaid'     => 'danger',
                            default      => 'gray',
                        })
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'paid'       => 'Lunas',
                            'dp_paid'    => 'DP Terbayar',
                            'dp_waiting' => 'Menunggu Verifikasi',
                            'unpaid'     => 'Belum Bayar',
                            default      => $state,
                        }),

                    TextEntry::make('created_at')
                        ->label('Waktu Booking')
                        ->dateTime('d M Y, H:i'),
                ]),

            Section::make('Bukti Pembayaran')
                ->schema([
                    RepeatableEntry::make('payments')
                        ->label('')
                        ->schema([
                            TextEntry::make('type')
                                ->label('Jenis')
                                ->badge()
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'dp'         => 'Down Payment',
                                    'pelunasan'  => 'Pelunasan',
                                    default      => $state,
                                })
                                ->color(fn (string $state): string => match ($state) {
                                    'dp'        => 'info',
                                    'pelunasan' => 'success',
                                    default     => 'gray',
                                }),

                            TextEntry::make('amount')
                                ->label('Jumlah')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                            TextEntry::make('sender_name')
                                ->label('Nama Pengirim')
                                ->placeholder('—'),

                            TextEntry::make('sender_bank')
                                ->label('Bank Pengirim')
                                ->placeholder('—'),

                            TextEntry::make('paid_at')
                                ->label('Waktu Transfer')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('—'),

                            TextEntry::make('status')
                                ->label('Status Verifikasi')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'verified' => 'success',
                                    'pending'  => 'warning',
                                    'rejected' => 'danger',
                                    default    => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'verified' => 'Terverifikasi',
                                    'pending'  => 'Menunggu',
                                    'rejected' => 'Ditolak',
                                    default    => $state,
                                }),

                            ImageEntry::make('proof_url')
                                ->label('Bukti Transfer')
                                ->imageHeight(200)
                                ->columnSpanFull()
                                ->placeholder('Tidak ada bukti.'),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ])
                ->collapsed()
                ->collapsible(),

            Section::make('Catatan')
                ->schema([
                    TextEntry::make('notes')
                        ->label('')
                        ->placeholder('Tidak ada catatan.')
                        ->columnSpanFull(),
                ])
                ->collapsed()
                ->collapsible(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vendorPackage.name')
                    ->label('Paket')
                    ->placeholder('—'),

                TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('promo_discount')
                    ->label('Potongan')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '—')
                    ->color('success'),

                TextColumn::make('agreed_total')
                    ->label('Total Bayar')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '—'),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'       => 'success',
                        'dp_paid'    => 'info',
                        'dp_waiting' => 'warning',
                        'unpaid'     => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid'       => 'Lunas',
                        'dp_paid'    => 'DP Terbayar',
                        'dp_waiting' => 'Menunggu Verifikasi',
                        'unpaid'     => 'Belum Bayar',
                        default      => $state,
                    }),

                TextColumn::make('event_date')
                    ->label('Tgl Acara')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Booking Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
