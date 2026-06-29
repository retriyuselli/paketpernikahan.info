<?php

namespace App\Filament\Admin\Resources\WeddingIncomingPayments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WeddingIncomingPaymentsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank_name')
                    ->label('Sumber Dana')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('transfer_date')
                    ->label('Tanggal Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sender_name')
                    ->label('Kontributor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Alokasi')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'confirmed' => 'success',
                        'rejected'  => 'danger',
                        default     => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'confirmed' => 'Dikonfirmasi',
                        'rejected'  => 'Ditolak',
                        default     => 'Menunggu',
                    }),

                TextColumn::make('confirmed_by')
                    ->label('Dikonfirmasi Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('confirmed_at')
                    ->label('Waktu Konfirmasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('transfer_date', 'desc')
            ->filters([
                SelectFilter::make('user')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected'  => 'Ditolak',
                    ]),
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status !== 'confirmed')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update([
                            'status'           => 'confirmed',
                            'confirmed_at'     => now(),
                            'confirmed_by'     => auth()->user()?->name,
                            'rejection_reason' => null,
                        ]);

                        Notification::make()
                            ->title('Uang masuk berhasil dikonfirmasi.')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status !== 'rejected')
                    ->schema([
                        TextInput::make('rejection_reason')
                            ->label('Alasan Ditolak')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'status'           => 'rejected',
                            'confirmed_at'     => null,
                            'confirmed_by'     => auth()->user()?->name,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Uang masuk ditolak.')
                            ->danger()
                            ->send();
                    }),

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
