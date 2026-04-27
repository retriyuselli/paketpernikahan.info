<?php

namespace App\Filament\Admin\Resources\RealWeddings;

use App\Filament\Admin\Resources\RealWeddings\Pages\CreateRealWedding;
use App\Filament\Admin\Resources\RealWeddings\Pages\EditRealWedding;
use App\Filament\Admin\Resources\RealWeddings\Pages\ListRealWeddings;
use App\Filament\Admin\Resources\RealWeddings\RelationManagers\VendorsRelationManager;
use App\Models\RealWedding;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class RealWeddingResource extends Resource
{
    protected static ?string $model = RealWedding::class;

    protected static ?string $navigationLabel = 'Real Weddings';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-heart';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Frontend';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\RealWeddingForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\RealWeddingsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            VendorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRealWeddings::route('/'),
            'create' => CreateRealWedding::route('/create'),
            'edit'   => EditRealWedding::route('/{record}/edit'),
        ];
    }
}
