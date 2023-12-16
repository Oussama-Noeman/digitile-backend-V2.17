<?php

namespace App\Filament\Resources\FreeDeliveryResource\Pages;

use App\Filament\Resources\FreeDeliveryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFreeDeliveries extends ListRecords
{
    protected static string $resource = FreeDeliveryResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
