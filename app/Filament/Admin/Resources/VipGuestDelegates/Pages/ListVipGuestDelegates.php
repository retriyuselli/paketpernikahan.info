<?php

namespace App\Filament\Admin\Resources\VipGuestDelegates\Pages;

use App\Filament\Admin\Resources\VipGuestDelegates\VipGuestDelegateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVipGuestDelegates extends ListRecords
{
    protected static string $resource = VipGuestDelegateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
