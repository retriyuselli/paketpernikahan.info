<?php

namespace App\Filament\Admin\Resources\CustomerPaymentMethods\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerPaymentMethodsTable
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
                    ->label('Bank / E-Wallet')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'bank'    => 'info',
                        'ewallet' => 'success',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bank'    => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                        default   => $state,
                    }),

                TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->searchable(),

                TextColumn::make('account_name')
                    ->label('Nama Pemilik')
                    ->searchable(),

                IconColumn::make('is_primary')
                    ->label('Utama')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'bank'    => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                    ]),
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
