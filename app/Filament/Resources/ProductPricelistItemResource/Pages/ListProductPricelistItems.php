<?php

namespace App\Filament\Resources\ProductPricelistItemResource\Pages;

use App\Filament\Resources\ProductPricelistItemResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductPricelistItems extends ListRecords
{
    protected static string $resource = ProductPricelistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
