<?php

namespace App\Filament\Resources\ProductAttributeValueResource\Pages;

use App\Filament\Resources\ProductAttributeValueResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductAttributeValues extends ListRecords
{
    protected static string $resource = ProductAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
           CreateAction::make(),
        ];
    }
}
