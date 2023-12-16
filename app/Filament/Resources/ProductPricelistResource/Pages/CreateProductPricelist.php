<?php

namespace App\Filament\Resources\ProductPricelistResource\Pages;

use App\Filament\Resources\ProductPricelistResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPricelist extends CreateRecord
{
    protected static string $resource = ProductPricelistResource::class;

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    public function getTitle(): string 
    {
        return __('Create PriceList');
    }
}
