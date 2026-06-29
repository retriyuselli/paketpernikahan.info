<?php

namespace App\Filament\Admin\Resources\WeddingIncomingPayments;

use App\Filament\Admin\Resources\WeddingIncomingPayments\Pages\CreateWeddingIncomingPayment;
use App\Filament\Admin\Resources\WeddingIncomingPayments\Pages\EditWeddingIncomingPayment;
use App\Filament\Admin\Resources\WeddingIncomingPayments\Pages\ListWeddingIncomingPayments;
use App\Models\WeddingIncomingPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WeddingIncomingPaymentResource extends Resource
{
    protected static ?string $model = WeddingIncomingPayment::class;

    protected static ?string $modelLabel = 'Uang Masuk';
    protected static ?string $pluralModelLabel = 'Uang Masuk';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\WeddingIncomingPaymentForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\WeddingIncomingPaymentsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWeddingIncomingPayments::route('/'),
            'create' => CreateWeddingIncomingPayment::route('/create'),
            'edit'   => EditWeddingIncomingPayment::route('/{record}/edit'),
        ];
    }
}
