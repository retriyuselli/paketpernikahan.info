<?php

namespace App\Filament\Admin\Resources\CustomerNotifications;

use App\Filament\Admin\Resources\CustomerNotifications\Pages\CreateCustomerNotification;
use App\Filament\Admin\Resources\CustomerNotifications\Pages\EditCustomerNotification;
use App\Filament\Admin\Resources\CustomerNotifications\Pages\ListCustomerNotifications;
use App\Models\CustomerNotification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CustomerNotificationResource extends Resource
{
    protected static ?string $model = CustomerNotification::class;

    protected static ?string $modelLabel = 'Notifikasi';
    protected static ?string $pluralModelLabel = 'Notifikasi Customer';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\CustomerNotificationForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\CustomerNotificationsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomerNotifications::route('/'),
            'create' => CreateCustomerNotification::route('/create'),
            'edit'   => EditCustomerNotification::route('/{record}/edit'),
        ];
    }
}
