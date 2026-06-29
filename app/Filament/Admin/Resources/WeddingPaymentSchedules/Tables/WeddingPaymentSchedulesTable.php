<?php

namespace App\Filament\Admin\Resources\WeddingPaymentSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WeddingPaymentSchedulesTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vendor_name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category_label')
                    ->label('Kategori')
                    ->badge(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'    => 'success',
                        'overdue' => 'danger',
                        default   => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'paid'    => 'Lunas',
                        'overdue' => 'Lewat Tempo',
                        default   => 'Pending',
                    }),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('due_date')
            ->filters([
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid'    => 'Lunas',
                        'overdue' => 'Lewat Tempo',
                    ]),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'venue'         => 'Venue',
                        'catering'      => 'Catering',
                        'decoration'    => 'Dekorasi',
                        'photo_video'   => 'Foto & Video',
                        'entertainment' => 'Entertainment',
                        'makeup'        => 'Makeup & Busana',
                        'transport'     => 'Transportasi',
                        'wo'            => 'Wedding Organizer',
                        'other'         => 'Lainnya',
                    ]),
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
