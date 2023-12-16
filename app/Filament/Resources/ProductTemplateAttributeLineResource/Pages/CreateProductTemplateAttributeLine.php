<?php

namespace App\Filament\Resources\ProductTemplateAttributeLineResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeLineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTemplateAttributeLine extends CreateRecord
{
    protected static string $resource = ProductTemplateAttributeLineResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
