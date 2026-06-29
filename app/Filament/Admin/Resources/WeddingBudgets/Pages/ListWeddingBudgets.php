<?php

namespace App\Filament\Admin\Resources\WeddingBudgets\Pages;

use App\Filament\Admin\Resources\WeddingBudgets\WeddingBudgetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeddingBudgets extends ListRecords
{
    protected static string $resource = WeddingBudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
