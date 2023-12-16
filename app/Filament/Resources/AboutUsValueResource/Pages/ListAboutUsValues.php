<?php

namespace App\Filament\Resources\AboutUsValueResource\Pages;

use App\Filament\Resources\AboutUsValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutUsValues extends ListRecords
{
    protected static string $resource = AboutUsValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}