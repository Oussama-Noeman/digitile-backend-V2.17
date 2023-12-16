<?php

namespace App\Filament\Resources\ProductTemplateAttributeLineResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeLineResource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductTemplateAttributeLines extends ListRecords
{
    protected static string $resource = ProductTemplateAttributeLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
          CreateAction::make(),
        ];
    }
}
