<?php

namespace App\Filament\Resources\HrJobResource\Pages;

use App\Filament\Resources\HrJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHrJobs extends ListRecords
{
    protected static string $resource = HrJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
