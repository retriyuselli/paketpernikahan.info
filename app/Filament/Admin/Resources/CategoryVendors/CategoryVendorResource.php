<?php

namespace App\Filament\Admin\Resources\CategoryVendors;

use App\Filament\Admin\Resources\CategoryVendors\Pages\CreateCategoryVendor;
use App\Filament\Admin\Resources\CategoryVendors\Pages\EditCategoryVendor;
use App\Filament\Admin\Resources\CategoryVendors\Pages\ListCategoryVendors;
use App\Filament\Admin\Resources\CategoryVendors\Schemas\CategoryVendorForm;
use App\Filament\Admin\Resources\CategoryVendors\Tables\CategoryVendorsTable;
use App\Models\CategoryVendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CategoryVendorResource extends Resource
{
    protected static ?string $model = CategoryVendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori Vendor';

    protected static string|UnitEnum|null $navigationGroup = 'Data Vendor';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CategoryVendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryVendorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryVendors::route('/'),
            'create' => CreateCategoryVendor::route('/create'),
            'edit' => EditCategoryVendor::route('/{record}/edit'),
        ];
    }
}
