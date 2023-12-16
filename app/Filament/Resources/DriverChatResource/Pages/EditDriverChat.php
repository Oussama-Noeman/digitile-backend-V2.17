<?php

namespace App\Filament\Resources\DriverChatResource\Pages;

use App\Filament\Resources\DriverChatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDriverChat extends EditRecord
{
    protected static string $resource = DriverChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
