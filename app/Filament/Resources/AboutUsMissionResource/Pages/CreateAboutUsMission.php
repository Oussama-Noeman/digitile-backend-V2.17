<?php

namespace App\Filament\Resources\AboutUsMissionResource\Pages;

use App\Filament\Resources\AboutUsMissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAboutUsMission extends CreateRecord
{
    protected static string $resource = AboutUsMissionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
