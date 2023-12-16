<?php

namespace App\Filament\Resources\MainPageSectionResource\Pages;

use App\Filament\Resources\MainPageSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMainPageSection extends CreateRecord
{
    protected static string $resource = MainPageSectionResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
}
