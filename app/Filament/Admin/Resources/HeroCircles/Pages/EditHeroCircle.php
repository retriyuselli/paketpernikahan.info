<?php

namespace App\Filament\Admin\Resources\HeroCircles\Pages;

use App\Filament\Admin\Resources\HeroCircles\HeroCircleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeroCircle extends EditRecord
{
    protected static string $resource = HeroCircleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
