<?php

namespace App\Filament\Resources\AboutUsMissionResource\Pages;

use App\Filament\Resources\AboutUsMissionResource;
use Filament\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutUsMission extends ViewRecord
{
    protected static string $resource = AboutUsMissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
