<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Schemas;

use App\Models\FamilyMember;
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

                    TextInput::make('no')
                        ->label('No')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(65535)
                        ->nullable(),

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

                    Select::make('rsvp_status')
                        ->label('Status RSVP')
                        ->options(FamilyMember::$rsvpOptions)
                        ->default('menunggu')
                        ->required(),
                ])->columns(2),
        ];
    }
}
