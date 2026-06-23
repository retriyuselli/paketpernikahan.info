<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class FamilyMemberForm
{
    public static function schema(): array
    {
        return [
            Section::make('Anggota Keluarga')
                ->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('role')
                        ->label('Peran')
                        ->placeholder('contoh: Ayah Pengantin Pria')
                        ->maxLength(80)
                        ->nullable(),

                    TextInput::make('phone')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->maxLength(30)
                        ->nullable(),
                ])->columns(2),
        ];
    }
}
