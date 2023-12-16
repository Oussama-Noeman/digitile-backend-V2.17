<?php

namespace App\Filament\Resources\FirstOrderResource\Pages;

use App\Filament\Resources\FirstOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFirstOrders extends ListRecords
{
    protected static string $resource = FirstOrderResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
