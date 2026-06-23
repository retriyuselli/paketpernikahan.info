<?php

namespace App\Filament\Admin\Resources\WeddingInfos\Tables;

use App\Models\WeddingEvent;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeddingInfosTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('groom_name')
                    ->label('Pengantin Pria')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('bride_name')
                    ->label('Pengantin Wanita')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('acara_summary')
                    ->label('Rangkaian Acara')
                    ->state(function ($record) {
                        $events = $record->events()
                            ->orderBy('tgl_acara')
                            ->get();

                        if ($events->isEmpty()) {
                            return '—';
                        }

                        return $events->map(function ($e) {
                            $label = WeddingEvent::$jenisOptions[$e->jenis_acara] ?? $e->jenis_acara;
                            $tgl   = $e->tgl_acara?->format('d M Y') ?? '?';
                            return "{$label}: {$tgl}";
                        })->implode(' · ');
                    })
                    ->wrap()
                    ->placeholder('—'),

                TextColumn::make('events_count')
                    ->label('Jml. Acara')
                    ->counts('events')
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
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
