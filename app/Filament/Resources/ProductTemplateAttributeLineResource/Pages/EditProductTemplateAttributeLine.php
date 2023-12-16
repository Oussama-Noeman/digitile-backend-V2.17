<?php

namespace App\Filament\Resources\ProductTemplateAttributeLineResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeLineResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductTemplateAttributeLine extends EditRecord
{
    protected static string $resource = ProductTemplateAttributeLineResource::class;

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
}
