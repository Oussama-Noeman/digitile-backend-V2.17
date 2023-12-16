<?php

namespace App\Filament\Resources\ProductProductResource\Pages;

use App\Filament\Resources\ProductProductResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductProduct extends EditRecord
{
    protected static string $resource = ProductProductResource::class;

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
