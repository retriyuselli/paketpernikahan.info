<?php

namespace App\Filament\Admin\Resources\HomeAds\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class HomeAdsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->size(56),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'card'   => 'Highlight Card',
                        'banner' => 'Banner',
                        default  => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'card'   => 'info',
                        'banner' => 'success',
                        default  => 'gray',
                    }),

                TextColumn::make('title')
                    ->label('Judul / Sponsor')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(50)
                    ->placeholder('—'),

                TextColumn::make('link_url')
                    ->label('URL Tujuan')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('delay_seconds')
                    ->label('Delay (s)')
                    ->alignCenter(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
