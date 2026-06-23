<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VipGuestDelegatesTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Label Akses')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('token')
                    ->label('Token')
                    ->limit(20)
                    ->copyable()
                    ->copyMessage('Token disalin')
                    ->fontFamily('mono')
                    ->tooltip(fn ($record) => $record->token),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->state(fn ($record) => $record->isActive())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('expires_at')
                    ->label('Kadaluarsa')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Tidak ada batas')
                    ->sortable(),

                TextColumn::make('last_accessed_at')
                    ->label('Terakhir Diakses')
                    ->since()
                    ->placeholder('Belum pernah')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_expired')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Kadaluarsa')
                    ->falseLabel('Aktif')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('expires_at')->where('expires_at', '<', now()),
                        false: fn ($query) => $query->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now())),
                    ),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->label('Cabut Akses'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Cabut Akses Terpilih'),
                ]),
            ]);
    }
}
