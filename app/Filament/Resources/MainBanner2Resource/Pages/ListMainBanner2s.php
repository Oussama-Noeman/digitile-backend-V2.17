<?php

namespace App\Filament\Resources\MainBanner2Resource\Pages;

use App\Filament\Resources\MainBanner2Resource;
use Filament\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMainBanner2s extends ListRecords
{
    protected static string $resource = MainBanner2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
          CreateAction::make(),
        ];
    }
}
