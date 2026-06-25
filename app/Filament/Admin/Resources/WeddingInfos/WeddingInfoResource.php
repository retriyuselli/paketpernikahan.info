<?php

namespace App\Filament\Admin\Resources\WeddingInfos;

use App\Filament\Admin\Resources\WeddingInfos\Pages\CreateWeddingInfo;
use App\Filament\Admin\Resources\WeddingInfos\Pages\EditWeddingInfo;
use App\Filament\Admin\Resources\WeddingInfos\Pages\ListWeddingInfos;
use App\Filament\Admin\Resources\WeddingInfos\RelationManagers\FamilyMembersRelationManager;
use App\Filament\Admin\Resources\WeddingInfos\RelationManagers\PreparationTasksRelationManager;
use App\Filament\Admin\Resources\WeddingInfos\RelationManagers\WeddingEventsRelationManager;
use App\Models\WeddingInfo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WeddingInfoResource extends Resource
{
    protected static ?string $model = WeddingInfo::class;

    protected static ?string $modelLabel = 'Info Pernikahan';
    protected static ?string $pluralModelLabel = 'Info Pernikahan';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\WeddingInfoForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\WeddingInfosTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            WeddingEventsRelationManager::class,
            PreparationTasksRelationManager::class,
            FamilyMembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWeddingInfos::route('/'),
            'create' => CreateWeddingInfo::route('/create'),
            'edit'   => EditWeddingInfo::route('/{record}/edit'),
        ];
    }
}
