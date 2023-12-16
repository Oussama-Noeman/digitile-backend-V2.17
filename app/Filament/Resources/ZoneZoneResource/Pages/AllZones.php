<?php

namespace App\Filament\Resources\ZoneZoneResource\Pages;

use App\Filament\Resources\ZoneZoneResource;
use App\Models\Tenant\ZoneZone;
use Filament\Resources\Pages\Page;

class AllZones extends Page
{
    protected static string $resource = ZoneZoneResource::class;
  
    protected static string $view = 'filament.resources.zone-zone-resource.pages.all-zones';
    protected function getViewData(): array {
        
        $zones =ZoneZone::all();
        
        $zonesData = [];

        foreach ($zones as $zone) {
            $zoneData = [
                'name' => $zone->name,
                'color'=>$zone->marker_color,
                'latitude_longitude' => []
            ];
        
            foreach ($zone->lattitude_longitudes as $latlong) {
                $zoneData['latitude_longitude'][] = [
                    'lat' => $latlong->latitude,
                    'lng' => $latlong->longitude
                ];
            }
        
            $zonesData[] = $zoneData;
        }
        
        $jsonData = json_encode($zonesData, JSON_PRETTY_PRINT);
       



        // get the authenticated user
        return compact('zones','jsonData');
    
}

}
