<?php

namespace App\Filament\Resources\MainBanner2Resource\Pages;

use App\Filament\Resources\MainBanner2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMainBanner2 extends CreateRecord
{
    protected static string $resource = MainBanner2Resource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
