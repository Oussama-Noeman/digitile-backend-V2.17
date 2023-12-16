<?php

namespace App\Filament\Resources\ResCompanyResource\Pages;

use App\Filament\Resources\ResCompanyResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResCompanies extends ListRecords
{
    protected static string $resource = ResCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
