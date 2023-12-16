<?php

namespace App\Filament\Resources\WebsiteFaqResource\Pages;

use App\Filament\Resources\WebsiteFaqResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteFaqs extends ListRecords
{
    protected static string $resource = WebsiteFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
