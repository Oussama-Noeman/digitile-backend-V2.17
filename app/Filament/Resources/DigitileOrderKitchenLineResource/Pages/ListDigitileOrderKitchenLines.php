<?php

namespace App\Filament\Resources\DigitileOrderKitchenLineResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenLineResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDigitileOrderKitchenLines extends ListRecords
{
    protected static string $resource = DigitileOrderKitchenLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
