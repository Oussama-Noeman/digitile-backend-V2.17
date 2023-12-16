<?php

namespace App\Filament\Resources\DigitileOrderKitchenLineResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenLineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDigitileOrderKitchenLine extends CreateRecord
{
    protected static string $resource = DigitileOrderKitchenLineResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
