<?php

namespace App\Filament\Admin\Resources\Promos\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PromosTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? 'Persentase' : 'Nominal')
                    ->color(fn ($state) => $state === 'percentage' ? 'info' : 'success'),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percentage'
                        ? $state . '%'
                        : 'Rp ' . number_format($state, 0, ',', '.')),

                TextColumn::make('uses_count')
                    ->label('Dipakai')
                    ->formatStateUsing(fn ($state, $record) => $state . ' / ' . ($record->max_uses ?? '∞')),

                TextColumn::make('valid_until')
                    ->label('Berlaku Hingga')
                    ->dateTime('d M Y')
                    ->placeholder('Tanpa batas'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Status'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
