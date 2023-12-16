<?php

namespace App\Filament\Resources\ProductPricelistItemResource\Pages;

use App\Filament\Resources\ProductPricelistItemResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductPricelistItem extends EditRecord
{
    protected static string $resource = ProductPricelistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
       DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Edit PriceList Item');
    }
}
