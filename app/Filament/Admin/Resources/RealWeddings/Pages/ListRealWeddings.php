<?php

namespace App\Filament\Admin\Resources\RealWeddings\Pages;

use App\Filament\Admin\Resources\RealWeddings\RealWeddingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRealWeddings extends ListRecords
{
    protected static string $resource = RealWeddingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
