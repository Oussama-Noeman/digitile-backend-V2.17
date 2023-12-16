<?php

namespace App\Filament\Resources\CareerInformationResource\Pages;

use App\Filament\Resources\CareerInformationResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCareerInformation extends ListRecords
{
    protected static string $resource = CareerInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
