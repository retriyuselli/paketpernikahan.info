<?php

namespace App\Filament\Admin\Resources\PartnerLogos;

use App\Filament\Admin\Resources\PartnerLogos\Pages\CreatePartnerLogo;
use App\Filament\Admin\Resources\PartnerLogos\Pages\EditPartnerLogo;
use App\Filament\Admin\Resources\PartnerLogos\Pages\ListPartnerLogos;
use App\Models\PartnerLogo;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PartnerLogoResource extends Resource
{
    protected static ?string $model = PartnerLogo::class;

    protected static ?string $modelLabel = 'Logo Partner';
    protected static ?string $pluralModelLabel = 'Logo Partner';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Frontend';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\PartnerLogoForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\PartnerLogosTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartnerLogos::route('/'),
            'create' => CreatePartnerLogo::route('/create'),
            'edit' => EditPartnerLogo::route('/{record}/edit'),
        ];
    }
}
