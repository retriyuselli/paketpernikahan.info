<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates\Pages;

use App\Filament\Admin\Resources\VipGuestDelegates\VipGuestDelegateResource;
use App\Models\VipGuestDelegate;
use Filament\Resources\Pages\CreateRecord;

class CreateVipGuestDelegate extends CreateRecord
{
    protected static string $resource = VipGuestDelegateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['token'] = VipGuestDelegate::generateToken();
        return $data;
    }
}
