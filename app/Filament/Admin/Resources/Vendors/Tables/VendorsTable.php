<?php

namespace App\Filament\Admin\Resources\Vendors\Tables;

use App\Models\CategoryVendor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://picsum.photos/seed/' . $record->slug . '-hero/80/80'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string =>
                        CategoryVendor::where('slug', $state)->value('name') ?? $state
                    )
                    ->color(fn (string $state): string =>
                        CategoryVendor::where('slug', $state)->value('color') ?? 'gray'
                    ),
                TextColumn::make('location')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price_start')
                    ->sortable(),
                TextColumn::make('rating')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('promo')
                    ->badge()
                    ->color('success')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(fn () => CategoryVendor::orderBy('sort_order')
                        ->pluck('name', 'slug')
                        ->toArray()
                    )
                    ->searchable(),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([1 => 'Aktif', 0 => 'Non-Aktif']),
            ])
            ->defaultSort('rating', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
