<?php

namespace App\Filament\Resources\FreeDeliveryResource\Pages;

use App\Filament\Resources\FreeDeliveryResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFreeDelivery extends EditRecord
{
    protected static string $resource = FreeDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
           DeleteAction::make(),
        ];
    }
}
