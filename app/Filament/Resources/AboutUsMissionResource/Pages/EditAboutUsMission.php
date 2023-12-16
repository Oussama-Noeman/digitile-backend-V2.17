<?php

namespace App\Filament\Resources\AboutUsMissionResource\Pages;

use App\Filament\Resources\AboutUsMissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutUsMission extends EditRecord
{
    protected static string $resource = AboutUsMissionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
