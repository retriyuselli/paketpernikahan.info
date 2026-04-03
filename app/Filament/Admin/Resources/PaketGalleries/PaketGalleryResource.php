<?php

namespace App\Filament\Admin\Resources\PaketGalleries;

use App\Filament\Admin\Resources\PaketGalleries\Pages\CreatePaketGallery;
use App\Filament\Admin\Resources\PaketGalleries\Pages\EditPaketGallery;
use App\Filament\Admin\Resources\PaketGalleries\Pages\ListPaketGalleries;
use App\Filament\Admin\Resources\PaketGalleries\Schemas\PaketGalleryForm;
use App\Filament\Admin\Resources\PaketGalleries\Tables\PaketGalleriesTable;
use App\Models\PaketGallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaketGalleryResource extends Resource
{
    protected static ?string $model = PaketGallery::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Store';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function form(Schema $schema): Schema
    {
        return PaketGalleryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaketGalleriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaketGalleries::route('/'),
            'create' => CreatePaketGallery::route('/create'),
            'edit' => EditPaketGallery::route('/{record}/edit'),
        ];
    }
}

