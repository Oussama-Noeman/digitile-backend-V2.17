<?php

namespace App\Filament\Resources\ProductTemplateAttributeValueResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeValueResource;
use App\Models\ProductTemplate;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductTemplateAttributeValues extends ListRecords
{
    protected static string $resource = ProductTemplateAttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
