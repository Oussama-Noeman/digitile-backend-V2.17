<?php

namespace App\Filament\Resources\HrJobResource\Pages;

use App\Filament\Resources\HrJobResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHrJobs extends ListRecords
{
    protected static string $resource = HrJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
