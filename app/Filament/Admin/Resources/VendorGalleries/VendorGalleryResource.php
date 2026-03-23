<?php

namespace App\Filament\Admin\Resources\VendorGalleries;

use App\Filament\Admin\Resources\VendorGalleries\Pages\CreateVendorGallery;
use App\Filament\Admin\Resources\VendorGalleries\Pages\EditVendorGallery;
use App\Filament\Admin\Resources\VendorGalleries\Pages\ListVendorGalleries;
use App\Filament\Admin\Resources\VendorGalleries\Schemas\VendorGalleryForm;
use App\Filament\Admin\Resources\VendorGalleries\Tables\VendorGalleriesTable;
use App\Models\VendorGallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorGalleryResource extends Resource
{
    protected static ?string $model = VendorGallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VendorGalleryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorGalleriesTable::configure($table);
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
            'index' => ListVendorGalleries::route('/'),
            'create' => CreateVendorGallery::route('/create'),
            'edit' => EditVendorGallery::route('/{record}/edit'),
        ];
    }
}
