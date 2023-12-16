<?php

namespace App\Filament\Resources\ZoneZoneResource\Pages;

use App\Filament\Resources\ZoneZoneResource;
use App\Models\LatitudeLongitude;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZoneZone extends CreateRecord
{
    protected static string $resource = ZoneZoneResource::class;
    public function getTitle(): string 
    {
        return __('Create Zone');
    }
   

}
