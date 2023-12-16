<?php

namespace App\Filament\Resources\OrderTripResource\Pages;

use App\Filament\Resources\OrderTripResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderTrips extends ListRecords
{
    protected static string $resource = OrderTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
           CreateAction::make(),
        ];
       
    }
    public $record;
}
