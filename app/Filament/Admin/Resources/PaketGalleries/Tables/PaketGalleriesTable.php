<?php

namespace App\Filament\Admin\Resources\PaketGalleries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaketGalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendorPackage.name')
                    ->label('Paket')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('caption')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('video_url')
                    ->label('Video')
                    ->limit(40)
                    ->url(fn ($record) => $record->video_url, true)
                    ->color('info'),
            ])
            ->filters([
                SelectFilter::make('vendor_package_id')
                    ->label('Paket')
                    ->relationship('vendorPackage', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('vendor_package_id');
    }
}
