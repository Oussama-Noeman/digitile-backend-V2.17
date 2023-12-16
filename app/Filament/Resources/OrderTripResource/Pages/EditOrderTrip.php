<?php

namespace App\Filament\Resources\OrderTripResource\Pages;

use App\Filament\Resources\OrderTripResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderTrip extends EditRecord
{
    protected static string $resource = OrderTripResource::class;

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
