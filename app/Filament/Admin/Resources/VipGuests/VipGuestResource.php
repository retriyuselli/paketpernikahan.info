<?php

namespace App\Filament\Admin\Resources\VipGuests;

use App\Filament\Admin\Resources\VipGuests\Pages\CreateVipGuest;
use App\Filament\Admin\Resources\VipGuests\Pages\EditVipGuest;
use App\Filament\Admin\Resources\VipGuests\Pages\ListVipGuests;
use App\Models\VipGuest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VipGuestResource extends Resource
{
    protected static ?string $model = VipGuest::class;

    protected static ?string $modelLabel = 'Tamu VIP';
    protected static ?string $pluralModelLabel = 'Tamu VIP';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\VipGuestForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\VipGuestsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVipGuests::route('/'),
            'create' => CreateVipGuest::route('/create'),
            'edit'   => EditVipGuest::route('/{record}/edit'),
        ];
    }
}
