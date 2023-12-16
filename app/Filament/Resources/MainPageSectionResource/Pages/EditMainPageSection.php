<?php

namespace App\Filament\Resources\MainPageSectionResource\Pages;

use App\Filament\Resources\MainPageSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMainPageSection extends EditRecord
{
    protected static string $resource = MainPageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
