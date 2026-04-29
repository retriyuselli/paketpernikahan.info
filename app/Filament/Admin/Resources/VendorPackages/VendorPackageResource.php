<?php

namespace App\Filament\Admin\Resources\VendorPackages;

use App\Filament\Admin\Resources\VendorPackages\Pages\CreateVendorPackage;
use App\Filament\Admin\Resources\VendorPackages\Pages\EditVendorPackage;
use App\Filament\Admin\Resources\VendorPackages\Pages\ListVendorPackages;
use App\Filament\Admin\Resources\VendorPackages\Schemas\VendorPackageForm;
use App\Filament\Admin\Resources\VendorPackages\Tables\VendorPackagesTable;
use App\Models\VendorPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorPackageResource extends Resource
{
    protected static ?string $model = VendorPackage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Vendor';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public static function form(Schema $schema): Schema
    {
        return VendorPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPackagesTable::configure($table);
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
            'index' => ListVendorPackages::route('/'),
            'create' => CreateVendorPackage::route('/create'),
            'edit' => EditVendorPackage::route('/{record}/edit'),
        ];
    }
}
