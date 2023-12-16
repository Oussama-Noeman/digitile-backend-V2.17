<?php

namespace App\Filament\Resources\ResPartnerResource\Pages;

use App\Filament\Resources\ResPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResPartners extends ListRecords
{
    protected static string $resource = ResPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
