<?php

namespace App\Filament\Resources\AboutUsValueResource\Pages;

use App\Filament\Resources\AboutUsValueResource;
use Filament\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutUsValue extends ViewRecord
{
    protected static string $resource = AboutUsValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
