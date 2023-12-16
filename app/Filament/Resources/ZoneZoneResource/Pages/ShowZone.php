<?php

namespace App\Filament\Resources\ZoneZoneResource\Pages;

use App\Filament\Resources\ZoneZoneResource;
use App\Models\ZoneZone;
use Filament\Resources\Pages\Page;

class ShowZone extends Page
{
    protected static string $resource = ZoneZoneResource::class;
    public $record;
    public function mount(ZoneZone $record)
    {
        $this->record = $record;
    }
    protected static string $view = 'filament.resources.zone-zone-resource.pages.show-zone';
   
}
