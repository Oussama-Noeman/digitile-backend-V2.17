<?php

namespace App\Forms\Components;

use App\Models\Tenant\ZoneZone;
use Filament\Forms\Components\Field;
class DrawZone extends Field
{
    protected string $view = 'forms.components.draw-zone';
    protected array $zones =[];
  
    public function getZones() {
        
        $pastzones =ZoneZone::all();
        
        $pastzonesData = [];

        foreach ($pastzones as $zone) {
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
        
            $pastzonesData[] = $zoneData;
            
        }
        
        $jsonData = json_encode($pastzonesData, JSON_PRETTY_PRINT);
       
     
       return $jsonData;
}


}
