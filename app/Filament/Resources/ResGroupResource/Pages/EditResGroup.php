<?php

namespace App\Filament\Resources\ResGroupResource\Pages;

use App\Filament\Resources\ResGroupResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResGroup extends EditRecord
{
    protected static string $resource = ResGroupResource::class;

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
        return __('Edit Group');
    }
}
