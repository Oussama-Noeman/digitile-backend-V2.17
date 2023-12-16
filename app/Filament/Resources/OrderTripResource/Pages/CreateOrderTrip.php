<?php

namespace App\Filament\Resources\OrderTripResource\Pages;

use App\Filament\Resources\OrderTripResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderTrip extends CreateRecord
{
    protected static string $resource = OrderTripResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
