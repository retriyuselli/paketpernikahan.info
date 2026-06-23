<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates;

use App\Filament\Admin\Resources\VipGuestDelegates\Pages\CreateVipGuestDelegate;
use App\Filament\Admin\Resources\VipGuestDelegates\Pages\EditVipGuestDelegate;
use App\Filament\Admin\Resources\VipGuestDelegates\Pages\ListVipGuestDelegates;
use App\Models\VipGuestDelegate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VipGuestDelegateResource extends Resource
{
    protected static ?string $model = VipGuestDelegate::class;

    protected static ?string $modelLabel = 'Akses Delegasi VIP';
    protected static ?string $pluralModelLabel = 'Akses Delegasi VIP';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\VipGuestDelegateForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\VipGuestDelegatesTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVipGuestDelegates::route('/'),
            'create' => CreateVipGuestDelegate::route('/create'),
            'edit'   => EditVipGuestDelegate::route('/{record}/edit'),
        ];
    }
}
