<?php

namespace App\Filament\Resources\ProductAttributeValueResource\Pages;

use App\Filament\Resources\ProductAttributeValueResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductAttributeValue extends EditRecord
{
    protected static string $resource = ProductAttributeValueResource::class;

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
        return __('Edit Product Attribute Value');
    }
}
