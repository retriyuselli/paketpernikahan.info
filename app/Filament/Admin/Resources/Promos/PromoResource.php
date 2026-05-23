<?php

namespace App\Filament\Admin\Resources\Promos;

use App\Filament\Admin\Resources\Promos\Pages\CreatePromo;
use App\Filament\Admin\Resources\Promos\Pages\EditPromo;
use App\Filament\Admin\Resources\Promos\Pages\ListPromos;
use App\Filament\Admin\Resources\Promos\RelationManagers\BookingsRelationManager;
use App\Models\Promo;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $modelLabel = 'Kode Promo';
    protected static ?string $pluralModelLabel = 'Kode Promo';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-tag';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\PromoForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\PromosTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPromos::route('/'),
            'create' => CreatePromo::route('/create'),
            'edit'   => EditPromo::route('/{record}/edit'),
        ];
    }
}
