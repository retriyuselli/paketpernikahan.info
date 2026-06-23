<?php

namespace App\Filament\Admin\Resources\FamilyMembers\Pages;

use App\Filament\Admin\Resources\FamilyMembers\FamilyMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFamilyMember extends CreateRecord
{
    protected static string $resource = FamilyMemberResource::class;
}
