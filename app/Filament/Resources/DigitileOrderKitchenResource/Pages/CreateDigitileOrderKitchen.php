<?php

namespace App\Filament\Resources\DigitileOrderKitchenResource\Pages;

use App\Filament\Resources\DigitileOrderKitchenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDigitileOrderKitchen extends CreateRecord
{
    protected static string $resource = DigitileOrderKitchenResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
