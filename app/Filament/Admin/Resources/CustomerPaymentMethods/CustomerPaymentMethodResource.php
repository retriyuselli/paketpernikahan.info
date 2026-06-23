<?php

namespace App\Filament\Admin\Resources\CustomerPaymentMethods;

use App\Filament\Admin\Resources\CustomerPaymentMethods\Pages\CreateCustomerPaymentMethod;
use App\Filament\Admin\Resources\CustomerPaymentMethods\Pages\EditCustomerPaymentMethod;
use App\Filament\Admin\Resources\CustomerPaymentMethods\Pages\ListCustomerPaymentMethods;
use App\Models\CustomerPaymentMethod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CustomerPaymentMethodResource extends Resource
{
    protected static ?string $model = CustomerPaymentMethod::class;

    protected static ?string $modelLabel = 'Metode Pembayaran';
    protected static ?string $pluralModelLabel = 'Metode Pembayaran Customer';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\CustomerPaymentMethodForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\CustomerPaymentMethodsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomerPaymentMethods::route('/'),
            'create' => CreateCustomerPaymentMethod::route('/create'),
            'edit'   => EditCustomerPaymentMethod::route('/{record}/edit'),
        ];
    }
}
