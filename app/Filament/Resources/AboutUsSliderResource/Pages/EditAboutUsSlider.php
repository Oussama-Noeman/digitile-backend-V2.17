<?php

namespace App\Filament\Resources\AboutUsSliderResource\Pages;

use App\Filament\Resources\AboutUsSliderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutUsSlider extends EditRecord
{
    protected static string $resource = AboutUsSliderResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
