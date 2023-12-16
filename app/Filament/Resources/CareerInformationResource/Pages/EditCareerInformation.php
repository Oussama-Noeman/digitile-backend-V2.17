<?php

namespace App\Filament\Resources\CareerInformationResource\Pages;

use App\Filament\Resources\CareerInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCareerInformation extends EditRecord
{
    protected static string $resource = CareerInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
