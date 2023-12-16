<?php

namespace App\Filament\Resources\ZoneZoneResource\Pages;

use App\Filament\Resources\ZoneZoneResource;
use App\Models\LatitudeLongitude;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZoneZone extends EditRecord
{
    protected static string $resource = ZoneZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
        // Retrieve the latitude and longitude data from the request data.
        $lat_longs = $this->data['zone'];
    
        // Retrieve the current zone record.
        $zone = $this->record;
        $zone_id = $zone->id;
    
      if($lat_longs){
            // Decode the JSON data and extract the coordinates.
            $lat_longs = json_decode($lat_longs, true)['features'][0]['geometry']['coordinates'][0];
    
            // Check if there are existing records for the current zone_id.
            $existingRecords = LatitudeLongitude::where('zone_id', $zone_id)->get();
        
            // Iterate through the new coordinates and update or create records.
            foreach ($lat_longs as $latt_long) {
                $latitude = $latt_long[1];
                $longitude = $latt_long[0];
        
                // Check if an existing record matches the latitude and longitude.
                $matchingRecord = $existingRecords->first(function ($record) use ($latitude, $longitude) {
                    return $record->latitude == $latitude && $record->longitude == $longitude;
                });
        
                if ($matchingRecord) {
                    // If a matching record exists, update it.
                    $matchingRecord->update([
                        'zone_id' => $zone_id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);
                } else {
                    // If no matching record exists, create a new record.
                    LatitudeLongitude::create([
                        'zone_id' => $zone_id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);
                }
            }
            
            // If there are any existing records not found in the new data, you can delete them if necessary.
            foreach ($existingRecords as $existingRecord) {
                $latitude = $existingRecord->latitude;
                $longitude = $existingRecord->longitude;
        
                $matchingNewRecord = collect($lat_longs)->first(function ($latt_long) use ($latitude, $longitude) {
                    return $latt_long[1] == $latitude && $latt_long[0] == $longitude;
                });
        
                if (!$matchingNewRecord) {
                    // Delete the existing record because it's not in the new data.
                    $existingRecord->delete();
                }
            }

      }
    }
    public function getTitle(): string 
    {
        return __('Edit Zone');
    }
    
}
