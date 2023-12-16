<?php

namespace App\Filament\Resources\MainPageSectionResource\Pages;

use App\Filament\Resources\MainPageSectionResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMainPageSections extends ListRecords
{
    protected static string $resource = MainPageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
