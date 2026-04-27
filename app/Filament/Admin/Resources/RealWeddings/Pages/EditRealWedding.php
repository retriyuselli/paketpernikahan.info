<?php

namespace App\Filament\Admin\Resources\RealWeddings\Pages;

use App\Filament\Admin\Resources\RealWeddings\RealWeddingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRealWedding extends EditRecord
{
    protected static string $resource = RealWeddingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
