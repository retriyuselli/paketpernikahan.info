<?php

namespace App\Filament\Admin\Resources\PushNotificationLogs;

use App\Filament\Admin\Resources\PushNotificationLogs\Pages\ListPushNotificationLogs;
use App\Models\PushNotificationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PushNotificationLogResource extends Resource
{
    protected static ?string $model = PushNotificationLog::class;

    protected static string|UnitEnum|null $navigationGroup = 'Notifikasi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Riwayat Notifikasi';

    protected static ?string $modelLabel = 'Riwayat Notifikasi';

    protected static ?string $pluralModelLabel = 'Riwayat Notifikasi';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return ListPushNotificationLogs::configureTable($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPushNotificationLogs::route('/'),
        ];
    }
}
