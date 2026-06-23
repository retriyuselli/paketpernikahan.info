<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates\Schemas;

use App\Models\VipGuestDelegate;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class VipGuestDelegateForm
{
    public static function schema(): array
    {
        return [
            Section::make('Akses Delegasi')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer (Pemilik)')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->label('Label Akses')
                        ->placeholder('contoh: Panitia Keluarga, MC, Tim Dokumentasi')
                        ->required()
                        ->maxLength(100),

                    Placeholder::make('token')
                        ->label('Token')
                        ->content(fn ($record) => $record?->token ?? '— (dibuat otomatis saat disimpan)')
                        ->helperText('Token digunakan sebagai kunci akses. Tidak dapat diubah.'),

                    DateTimePicker::make('expires_at')
                        ->label('Kadaluarsa')
                        ->nullable()
                        ->helperText('Kosongkan jika tidak ada batas waktu.'),

                    Placeholder::make('last_accessed_at')
                        ->label('Terakhir Diakses')
                        ->content(fn ($record) => $record?->last_accessed_at?->diffForHumans() ?? '— belum pernah diakses'),

                    Placeholder::make('is_active')
                        ->label('Status')
                        ->content(fn ($record) => $record
                            ? ($record->isActive() ? '✅ Aktif' : '🔴 Kadaluarsa')
                            : '—'
                        ),
                ])->columns(2),
        ];
    }
}
