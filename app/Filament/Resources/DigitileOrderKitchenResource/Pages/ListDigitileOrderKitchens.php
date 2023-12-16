<?php

namespace App\Filament\Resources\DigitileOrderKitchenResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDigitileOrderKitchens extends ListRecords
{
    protected static string $resource = DigitileOrderKitchenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
