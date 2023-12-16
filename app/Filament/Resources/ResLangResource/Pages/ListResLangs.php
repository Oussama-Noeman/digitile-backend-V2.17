<?php

namespace App\Filament\Resources\ResLangResource\Pages;

use App\Filament\Resources\ResLangResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResLangs extends ListRecords
{
    protected static string $resource = ResLangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
