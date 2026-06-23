<?php

namespace App\Filament\Admin\Resources\VipGuests\Pages;

use App\Filament\Admin\Resources\VipGuests\VipGuestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVipGuest extends CreateRecord
{
    protected static string $resource = VipGuestResource::class;
}
