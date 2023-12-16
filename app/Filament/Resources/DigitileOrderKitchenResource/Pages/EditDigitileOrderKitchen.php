<?php

namespace App\Filament\Resources\DigitileOrderKitchenResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDigitileOrderKitchen extends EditRecord
{
    protected static string $resource = DigitileOrderKitchenResource::class;

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
