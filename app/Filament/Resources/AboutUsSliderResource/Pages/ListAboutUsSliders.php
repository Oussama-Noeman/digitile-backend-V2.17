<?php

namespace App\Filament\Resources\AboutUsSliderResource\Pages;

use App\Filament\Resources\AboutUsSliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutUsSliders extends ListRecords
{
    protected static string $resource = AboutUsSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
