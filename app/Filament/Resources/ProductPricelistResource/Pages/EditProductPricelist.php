<?php

namespace App\Filament\Resources\ProductPricelistResource\Pages;

use App\Filament\Resources\ProductPricelistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductPricelist extends EditRecord
{
    protected static string $resource = ProductPricelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    public function getTitle(): string 
    {
        return __('Edit PriceList');
    }
}
