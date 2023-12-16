<?php

namespace App\Filament\Resources\ZoneZoneResource\Pages;

use App\Filament\Resources\ZoneZoneResource;

use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Redirect;
use Filament\Pages\Actions;
class ListZoneZones extends ListRecords
{
    protected static string $resource = ZoneZoneResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Zone')->translateLabel(),
            Action::make('show zones')
            ->translateLabel()
       
            ->url(fn (): string => route('filament.resources.tenant/zone-zones.all'))
           
        ];
    }
  
}
