<?php

namespace App\Filament\Admin\Resources\CustomerNotifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomerNotificationsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Pembayaran' => 'warning',
                        'Persiapan'  => 'info',
                        'Booking'    => 'success',
                        'Promo'      => 'danger',
                        default      => 'gray',
                    })
                    ->placeholder('—'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('destination')
                    ->label('Tujuan')
                    ->placeholder('—'),

                IconColumn::make('is_unread')
                    ->label('Belum Dibaca')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'Pembayaran' => 'Pembayaran',
                        'Persiapan'  => 'Persiapan',
                        'Booking'    => 'Booking',
                        'Promo'      => 'Promo',
                        'Sistem'     => 'Sistem',
                    ]),
                TernaryFilter::make('is_unread')
                    ->label('Status Baca')
                    ->trueLabel('Belum Dibaca')
                    ->falseLabel('Sudah Dibaca'),
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
