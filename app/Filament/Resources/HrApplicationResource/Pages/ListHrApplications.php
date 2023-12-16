<?php

namespace App\Filament\Resources\HrApplicationResource\Pages;

use App\Filament\Resources\HrApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHrApplications extends ListRecords
{
    protected static string $resource = HrApplicationResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
