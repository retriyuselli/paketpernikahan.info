<?php

namespace App\Filament\Admin\Resources\HomeAds\Pages;

use App\Filament\Admin\Resources\HomeAds\HomeAdResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeAd extends CreateRecord
{
    protected static string $resource = HomeAdResource::class;
}
