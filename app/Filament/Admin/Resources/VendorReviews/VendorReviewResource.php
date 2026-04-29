<?php

namespace App\Filament\Admin\Resources\VendorReviews;

use App\Filament\Admin\Resources\VendorReviews\Pages\CreateVendorReview;
use App\Filament\Admin\Resources\VendorReviews\Pages\EditVendorReview;
use App\Filament\Admin\Resources\VendorReviews\Pages\ListVendorReviews;
use App\Filament\Admin\Resources\VendorReviews\Schemas\VendorReviewForm;
use App\Filament\Admin\Resources\VendorReviews\Tables\VendorReviewsTable;
use App\Models\VendorReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorReviewResource extends Resource
{
    protected static ?string $model = VendorReview::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Vendor';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    public static function form(Schema $schema): Schema
    {
        return VendorReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorReviewsTable::configure($table);
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
            'index' => ListVendorReviews::route('/'),
            'create' => CreateVendorReview::route('/create'),
            'edit' => EditVendorReview::route('/{record}/edit'),
        ];
    }
}
