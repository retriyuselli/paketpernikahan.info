<?php

namespace App\Filament\Admin\Resources\WeddingBudgets;

use App\Filament\Admin\Resources\WeddingBudgets\Pages\CreateWeddingBudget;
use App\Filament\Admin\Resources\WeddingBudgets\Pages\EditWeddingBudget;
use App\Filament\Admin\Resources\WeddingBudgets\Pages\ListWeddingBudgets;
use App\Models\WeddingBudget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WeddingBudgetResource extends Resource
{
    protected static ?string $model = WeddingBudget::class;

    protected static ?string $modelLabel = 'Budget Pernikahan';
    protected static ?string $pluralModelLabel = 'Budget Pernikahan';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\WeddingBudgetForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\WeddingBudgetsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWeddingBudgets::route('/'),
            'create' => CreateWeddingBudget::route('/create'),
            'edit'   => EditWeddingBudget::route('/{record}/edit'),
        ];
    }
}
