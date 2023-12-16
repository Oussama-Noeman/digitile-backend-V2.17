<?php

namespace App\Filament\Resources\AboutUsValueResource\Pages;

use App\Filament\Resources\AboutUsValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutUsValue extends EditRecord
{
    protected static string $resource = AboutUsValueResource::class;
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
