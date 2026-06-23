<?php

namespace App\Filament\Admin\Resources\CustomerPreparationSections;

use App\Filament\Admin\Resources\CustomerPreparationSections\Pages\CreateCustomerPreparationSection;
use App\Filament\Admin\Resources\CustomerPreparationSections\Pages\EditCustomerPreparationSection;
use App\Filament\Admin\Resources\CustomerPreparationSections\Pages\ListCustomerPreparationSections;
use App\Filament\Admin\Resources\CustomerPreparationSections\RelationManagers\TasksRelationManager;
use App\Models\CustomerPreparationSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CustomerPreparationSectionResource extends Resource
{
    protected static ?string $model = CustomerPreparationSection::class;

    protected static ?string $modelLabel = 'Seksi Persiapan';
    protected static ?string $pluralModelLabel = 'Persiapan Customer';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\CustomerPreparationSectionForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\CustomerPreparationSectionsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomerPreparationSections::route('/'),
            'create' => CreateCustomerPreparationSection::route('/create'),
            'edit'   => EditCustomerPreparationSection::route('/{record}/edit'),
        ];
    }
}
