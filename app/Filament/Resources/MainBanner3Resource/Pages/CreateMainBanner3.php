<?php

namespace App\Filament\Resources\MainBanner3Resource\Pages;

use App\Filament\Resources\MainBanner3Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMainBanner3 extends CreateRecord
{
    protected static string $resource = MainBanner3Resource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}
