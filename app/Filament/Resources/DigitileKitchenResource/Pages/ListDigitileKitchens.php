<?php

namespace App\Filament\Resources\DigitileKitchenResource\Pages;

use App\Filament\Resources\DigitileKitchenResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDigitileKitchens extends ListRecords
{
    protected static string $resource = DigitileKitchenResource::class;

    protected function getHeaderActions(): array
    {
        return [
           CreateAction::make(),
        ];
    }
}
