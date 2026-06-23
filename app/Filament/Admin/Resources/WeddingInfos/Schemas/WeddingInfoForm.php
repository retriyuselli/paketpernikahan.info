<?php

namespace App\Filament\Admin\Resources\WeddingInfos\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WeddingInfoForm
{
    public static function schema(): array
    {
        return [
            Section::make('Info Pernikahan')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('groom_name')
                        ->label('Nama Pengantin Pria')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('bride_name')
                        ->label('Nama Pengantin Wanita')
                        ->maxLength(100)
                        ->nullable(),
                ])->columns(2),
        ];
    }
}
