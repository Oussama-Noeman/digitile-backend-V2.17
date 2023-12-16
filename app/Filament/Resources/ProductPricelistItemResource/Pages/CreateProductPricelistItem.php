<?php

namespace App\Filament\Resources\ProductPricelistItemResource\Pages;

use App\Filament\Resources\ProductPricelistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPricelistItem extends CreateRecord
{
    protected static string $resource = ProductPricelistItemResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create PriceList Item');
    }
}
