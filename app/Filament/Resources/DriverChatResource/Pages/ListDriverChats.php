<?php

namespace App\Filament\Resources\DriverChatResource\Pages;

use App\Filament\Resources\DriverChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDriverChats extends ListRecords
{
    protected static string $resource = DriverChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
