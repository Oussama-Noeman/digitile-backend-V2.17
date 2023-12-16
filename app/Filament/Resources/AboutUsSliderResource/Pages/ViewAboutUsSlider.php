<?php

namespace App\Filament\Resources\AboutUsSliderResource\Pages;

use App\Filament\Resources\AboutUsSliderResource;
use Filament\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutUsSlider extends ViewRecord
{
    protected static string $resource = AboutUsSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
