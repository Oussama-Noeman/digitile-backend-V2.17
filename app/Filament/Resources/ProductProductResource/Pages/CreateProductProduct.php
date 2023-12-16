<?php

namespace App\Filament\Resources\ProductProductResource\Pages;

use App\Filament\Resources\ProductProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductProduct extends CreateRecord
{
    protected static string $resource = ProductProductResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
