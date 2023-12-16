<?php

namespace App\Filament\Resources\AboutUsMissionResource\Pages;

use App\Filament\Resources\AboutUsMissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutUsMissions extends ListRecords
{
    protected static string $resource = AboutUsMissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
