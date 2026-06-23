<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates\Pages;

use App\Filament\Admin\Resources\VipGuestDelegates\VipGuestDelegateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVipGuestDelegate extends EditRecord
{
    protected static string $resource = VipGuestDelegateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Cabut Akses'),
        ];
    }
}
