<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Tables;

use App\Models\FamilyMember;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FamilyMembersTable
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
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Peran')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telepon')
                    ->placeholder('—'),

                TextColumn::make('rsvp_status')
                    ->label('RSVP')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'hadir'        => 'success',
                        'tidak_hadir'  => 'danger',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => FamilyMember::$rsvpOptions[$state] ?? $state),

                TextColumn::make('rsvp_updated_by_name')
                    ->label('Update RSVP Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('rsvp_updated_at')
                    ->label('Update RSVP')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('no')
            ->filters([
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('rsvp_status')
                    ->label('Status RSVP')
                    ->options(FamilyMember::$rsvpOptions),
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
