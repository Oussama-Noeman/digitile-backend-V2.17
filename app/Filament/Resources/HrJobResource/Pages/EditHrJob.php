<?php

namespace App\Filament\Resources\HrJobResource\Pages;

use App\Filament\Resources\HrJobResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHrJob extends EditRecord
{
    protected static string $resource = HrJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
