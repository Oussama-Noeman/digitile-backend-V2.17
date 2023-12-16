<?php

namespace App\Filament\Resources\ResLangResource\Pages;

use App\Filament\Resources\ResLangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResLang extends CreateRecord
{
    protected static string $resource = ResLangResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create Lang');
    }
}
