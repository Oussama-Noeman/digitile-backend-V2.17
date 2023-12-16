<?php

namespace App\Filament\Resources\ResLangResource\Pages;

use App\Filament\Resources\ResLangResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResLang extends EditRecord
{
    protected static string $resource = ResLangResource::class;

    protected function getHeaderActions(): array
    {
        return [
        DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Edit Lang');
    }
}
