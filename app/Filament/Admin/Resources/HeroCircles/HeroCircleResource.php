<?php

namespace App\Filament\Admin\Resources\HeroCircles;

use App\Filament\Admin\Resources\HeroCircles\Pages\CreateHeroCircle;
use App\Filament\Admin\Resources\HeroCircles\Pages\EditHeroCircle;
use App\Filament\Admin\Resources\HeroCircles\Pages\ListHeroCircles;
use App\Models\HeroCircle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HeroCircleResource extends Resource
{
    protected static ?string $model = HeroCircle::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-sparkles';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Frontend';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\HeroCircleForm::schema());
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Admin\Resources\HeroCircles\Tables\HeroCirclesTable::table($table);
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
            'index' => ListHeroCircles::route('/'),
            'create' => CreateHeroCircle::route('/create'),
            'edit' => EditHeroCircle::route('/{record}/edit'),
        ];
    }
}
