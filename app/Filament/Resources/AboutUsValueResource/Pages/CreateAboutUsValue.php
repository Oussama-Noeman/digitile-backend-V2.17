<?php

namespace App\Filament\Resources\AboutUsValueResource\Pages;

use App\Filament\Resources\AboutUsValueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAboutUsValue extends CreateRecord
{
    protected static string $resource = AboutUsValueResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
