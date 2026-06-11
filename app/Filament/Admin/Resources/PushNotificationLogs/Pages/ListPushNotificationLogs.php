<?php

namespace App\Filament\Admin\Resources\PushNotificationLogs\Pages;

use App\Filament\Admin\Resources\PushNotificationLogs\PushNotificationLogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ListPushNotificationLogs extends ListRecords
{
    protected static string $resource = PushNotificationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function configureTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Kirim')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('target')
                    ->label('Target')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'all'   => 'primary',
                        'user'  => 'success',
                        'guest' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'all'   => 'Semua Device',
                        'user'  => 'Pengguna',
                        'guest' => 'Tamu',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penerima')
                    ->default('-')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('body')
                    ->label('Isi')
                    ->searchable()
                    ->wrap()
                    ->limit(80)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sent_count')
                    ->label('Terkirim')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('failed_count')
                    ->label('Gagal')
                    ->badge()
                    ->color(fn (int $state) => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sender.name')
                    ->label('Dikirim Oleh')
                    ->default('-')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('target')
                    ->label('Target')
                    ->options([
                        'all'   => 'Semua Device',
                        'user'  => 'Pengguna',
                        'guest' => 'Tamu',
                    ]),
            ])
            ->actions([])
            ->bulkActions([])
            ->paginated([25, 50, 100]);
    }
}
