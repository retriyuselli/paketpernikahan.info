<?php

namespace App\Filament\Admin\Resources\HeroCircles\Pages;

use App\Filament\Admin\Resources\HeroCircles\HeroCircleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHeroCircles extends ListRecords
{
    protected static string $resource = HeroCircleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
