<?php

namespace App\Filament\Resources\ResGroupResource\Pages;

use App\Filament\Resources\ResGroupResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResGroups extends ListRecords
{
    protected static string $resource = ResGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
          CreateAction::make(),
        ];
    }
}
