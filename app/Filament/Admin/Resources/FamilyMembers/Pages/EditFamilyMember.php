<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Pages;

use App\Filament\Admin\Resources\FamilyMembers\FamilyMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFamilyMember extends EditRecord
{
    protected static string $resource = FamilyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
