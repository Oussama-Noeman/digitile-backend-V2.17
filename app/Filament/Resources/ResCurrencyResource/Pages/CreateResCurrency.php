<?php

namespace App\Filament\Resources\ResCurrencyResource\Pages;

use App\Filament\Resources\ResCurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResCurrency extends CreateRecord
{
    protected static string $resource = ResCurrencyResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create Currency');
    }
}
