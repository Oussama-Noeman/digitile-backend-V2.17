<?php

namespace App\Filament\Resources\ProductPricelistResource\Pages;

use App\Filament\Resources\ProductPricelistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductPricelists extends ListRecords
{
    protected static string $resource = ProductPricelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
