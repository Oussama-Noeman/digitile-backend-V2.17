<?php

namespace App\Filament\Resources\DigitileOrderKitchenLineResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenLineResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDigitileOrderKitchenLine extends EditRecord
{
    protected static string $resource = DigitileOrderKitchenLineResource::class;

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
