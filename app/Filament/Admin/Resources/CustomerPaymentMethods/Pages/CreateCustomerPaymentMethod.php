<?php

namespace App\Filament\Admin\Resources\CustomerPaymentMethods\Pages;

use App\Filament\Admin\Resources\CustomerPaymentMethods\CustomerPaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPaymentMethod extends CreateRecord
{
    protected static string $resource = CustomerPaymentMethodResource::class;
}
