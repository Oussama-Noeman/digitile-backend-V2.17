<?php

namespace App\Filament\Resources\AboutUsVisionResource\Pages;

use App\Filament\Resources\AboutUsVisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutUsVision extends EditRecord
{
    protected static string $resource = AboutUsVisionResource::class;
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
