<?php

namespace App\Filament\Resources\MainBanner1Resource\Pages;

use App\Filament\Resources\MainBanner1Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMainBanner1 extends CreateRecord
{
    protected static string $resource = MainBanner1Resource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
