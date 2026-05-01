<?php

namespace App\Filament\Admin\Resources\VendorPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VendorPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Foto')
                    ->getStateUsing(fn ($record) => $record->image_url ? url($record->image_url) : null)
                    ->circular()
                    ->size(48)
                    ->defaultImageUrl(null),
                TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->categoryVendor?->name),
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->type ? 'Tipe: ' . ucfirst($record->type) : null),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Diskon')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('max_guests')
                    ->label('Maks. Tamu')
                    ->suffix(' tamu')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' pax')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                ColorColumn::make('card_color')
                    ->label('Warna Kartu')
                    ->copyable()
                    ->copyMessage('Warna disalin'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('vendor_id')
                    ->label('Vendor')
                    ->relationship('vendor', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('category_vendor_id')
                    ->label('Kategori')
                    ->relationship('categoryVendor', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options(fn () => \App\Models\VendorPackage::query()
                        ->whereNotNull('type')
                        ->distinct()
                        ->pluck('type', 'type')
                        ->mapWithKeys(fn ($v) => [$v => ucfirst($v)])
                        ->toArray()),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('vendor_id')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
