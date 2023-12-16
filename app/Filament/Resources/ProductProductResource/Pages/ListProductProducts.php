<?php

namespace App\Filament\Resources\ProductProductResource\Pages;

use App\Filament\Resources\ProductProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductProducts extends ListRecords
{
    protected static string $resource = ProductProductResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
