<?php

namespace App\Filament\Resources\AboutUsSliderResource\Pages;

use App\Filament\Resources\AboutUsSliderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAboutUsSlider extends CreateRecord
{
    protected static string $resource = AboutUsSliderResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
