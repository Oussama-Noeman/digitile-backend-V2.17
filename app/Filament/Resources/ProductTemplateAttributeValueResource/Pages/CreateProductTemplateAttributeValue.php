<?php

namespace App\Filament\Resources\ProductTemplateAttributeValueResource\Pages;

use App\Filament\Resources\ProductTemplateAttributeValueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTemplateAttributeValue extends CreateRecord
{
    protected static string $resource = ProductTemplateAttributeValueResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
