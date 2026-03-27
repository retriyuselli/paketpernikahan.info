<?php

namespace App\Filament\Admin\Resources\VendorReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VendorReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reviewer_name')
                    ->label('Reviewer')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Ulasan')
                    ->limit(60),
                TextColumn::make('reviewed_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                IconColumn::make('admin_reply')
                    ->label('Dibalas')
                    ->boolean(fn ($state) => filled($state)),
                IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('vendor_id')
                    ->label('Vendor')
                    ->relationship('vendor', 'name'),
                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([1=>'1 Bintang',2=>'2 Bintang',3=>'3 Bintang',4=>'4 Bintang',5=>'5 Bintang']),
                TernaryFilter::make('is_approved')
                    ->label('Status Persetujuan'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('reviewed_at', 'desc');
    }
}
