<?php

namespace App\Filament\Admin\Resources\FamilyMembers;

use App\Filament\Admin\Resources\FamilyMembers\Pages\CreateFamilyMember;
use App\Filament\Admin\Resources\FamilyMembers\Pages\EditFamilyMember;
use App\Filament\Admin\Resources\FamilyMembers\Pages\ListFamilyMembers;
use App\Models\FamilyMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;

    protected static ?string $modelLabel = 'Anggota Keluarga';
    protected static ?string $pluralModelLabel = 'Anggota Keluarga';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\FamilyMemberForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\FamilyMembersTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFamilyMembers::route('/'),
            'create' => CreateFamilyMember::route('/create'),
            'edit'   => EditFamilyMember::route('/{record}/edit'),
        ];
    }
}
