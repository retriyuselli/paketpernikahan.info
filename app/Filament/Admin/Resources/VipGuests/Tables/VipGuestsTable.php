<?php

namespace App\Filament\Admin\Resources\VipGuests\Tables;

use App\Models\VipGuest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VipGuestsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no')
                    ->label('No')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('name')
                    ->label('Nama Tamu')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->placeholder('—')
                    ->limit(40),

                TextColumn::make('instansi')
                    ->label('Instansi')
                    ->placeholder('—')
                    ->limit(40),

                TextColumn::make('phone')
                    ->label('Telepon')
                    ->placeholder('—'),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pejabat'          => 'warning',
                        'tokoh_masyarakat' => 'info',
                        'keluarga_besar'   => 'success',
                        'rekan_bisnis'     => 'primary',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => VipGuest::$kategoriOptions[$state] ?? $state),

                TextColumn::make('rsvp_status')
                    ->label('RSVP')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'hadir'        => 'success',
                        'tidak_hadir'  => 'danger',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => VipGuest::$rsvpOptions[$state] ?? $state),
            ])
            ->defaultSort('no')
            ->filters([
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options(VipGuest::$kategoriOptions),

                SelectFilter::make('rsvp_status')
                    ->label('Status RSVP')
                    ->options(VipGuest::$rsvpOptions),
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
