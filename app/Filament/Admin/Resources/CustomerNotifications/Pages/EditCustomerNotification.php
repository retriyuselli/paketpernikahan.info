<?php

namespace App\Filament\Admin\Resources\CustomerNotifications\Pages;

use App\Filament\Admin\Resources\CustomerNotifications\CustomerNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerNotification extends EditRecord
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
