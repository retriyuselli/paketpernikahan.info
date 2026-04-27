<?php

namespace App\Filament\Admin\Resources\HomeAds;

use App\Filament\Admin\Resources\HomeAds\Pages\CreateHomeAd;
use App\Filament\Admin\Resources\HomeAds\Pages\EditHomeAd;
use App\Filament\Admin\Resources\HomeAds\Pages\ListHomeAds;
use App\Models\HomeAd;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class HomeAdResource extends Resource
{
    protected static ?string $model = HomeAd::class;

    protected static ?string $modelLabel = 'Iklan Popup';
    protected static ?string $pluralModelLabel = 'Iklan Popup';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-megaphone';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Frontend';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\HomeAdForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\HomeAdsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListHomeAds::route('/'),
            'create' => CreateHomeAd::route('/create'),
            'edit'   => EditHomeAd::route('/{record}/edit'),
        ];
    }
}
