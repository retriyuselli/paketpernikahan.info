<?php

namespace App\Filament\Admin\Resources\CustomerNotifications\Pages;

use App\Filament\Admin\Resources\CustomerNotifications\CustomerNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerNotifications extends ListRecords
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
