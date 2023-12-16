<?php

namespace App\Filament\Resources\ResCurrencyResource\Pages;

use App\Filament\Resources\ResCurrencyResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResCurrencies extends ListRecords
{
    protected static string $resource = ResCurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
         CreateAction::make(),
        ];
    }
}
