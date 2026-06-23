<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Pages;

use App\Filament\Admin\Resources\FamilyMembers\FamilyMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFamilyMembers extends ListRecords
{
    protected static string $resource = FamilyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
