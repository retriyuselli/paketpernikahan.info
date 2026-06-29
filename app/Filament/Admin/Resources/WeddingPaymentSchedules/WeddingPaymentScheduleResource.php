<?php

namespace App\Filament\Admin\Resources\WeddingPaymentSchedules;

use App\Filament\Admin\Resources\WeddingPaymentSchedules\Pages\CreateWeddingPaymentSchedule;
use App\Filament\Admin\Resources\WeddingPaymentSchedules\Pages\EditWeddingPaymentSchedule;
use App\Filament\Admin\Resources\WeddingPaymentSchedules\Pages\ListWeddingPaymentSchedules;
use App\Models\WeddingPaymentSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WeddingPaymentScheduleResource extends Resource
{
    protected static ?string $model = WeddingPaymentSchedule::class;

    protected static ?string $modelLabel = 'Jadwal Pembayaran';
    protected static ?string $pluralModelLabel = 'Jadwal Pembayaran';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\WeddingPaymentScheduleForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\WeddingPaymentSchedulesTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWeddingPaymentSchedules::route('/'),
            'create' => CreateWeddingPaymentSchedule::route('/create'),
            'edit'   => EditWeddingPaymentSchedule::route('/{record}/edit'),
        ];
    }
}
