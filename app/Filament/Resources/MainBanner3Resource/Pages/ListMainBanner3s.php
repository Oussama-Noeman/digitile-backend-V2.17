<?php

namespace App\Filament\Resources\MainBanner3Resource\Pages;

use App\Filament\Resources\MainBanner3Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMainBanner3s extends ListRecords
{
    protected static string $resource = MainBanner3Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
