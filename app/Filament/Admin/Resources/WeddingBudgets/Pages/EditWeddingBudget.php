<?php

namespace App\Filament\Admin\Resources\WeddingBudgets\Pages;

use App\Filament\Admin\Resources\WeddingBudgets\WeddingBudgetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeddingBudget extends EditRecord
{
    protected static string $resource = WeddingBudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
