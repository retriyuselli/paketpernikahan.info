<?php

namespace App\Filament\Admin\Resources\CustomerNotifications\Pages;

use App\Filament\Admin\Resources\CustomerNotifications\CustomerNotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerNotification extends CreateRecord
{
    protected static string $resource = CustomerNotificationResource::class;
}
