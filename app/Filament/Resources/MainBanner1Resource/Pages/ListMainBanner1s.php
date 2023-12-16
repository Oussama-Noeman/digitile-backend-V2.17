<?php

namespace App\Filament\Resources\MainBanner1Resource\Pages;

use App\Filament\Resources\MainBanner1Resource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMainBanner1s extends ListRecords
{
    protected static string $resource = MainBanner1Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
