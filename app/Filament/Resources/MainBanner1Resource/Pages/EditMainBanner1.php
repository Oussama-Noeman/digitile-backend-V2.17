<?php

namespace App\Filament\Resources\MainBanner1Resource\Pages;

use App\Filament\Resources\MainBanner1Resource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMainBanner1 extends EditRecord
{
    protected static string $resource = MainBanner1Resource::class;

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
}
