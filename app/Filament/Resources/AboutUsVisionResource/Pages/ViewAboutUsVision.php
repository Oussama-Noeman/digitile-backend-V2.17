<?php

namespace App\Filament\Resources\AboutUsVisionResource\Pages;

use App\Filament\Resources\AboutUsVisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutUsVision extends ViewRecord
{
    protected static string $resource = AboutUsVisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
