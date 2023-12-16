<?php

namespace App\Filament\Resources\MainBanner3Resource\Pages;

use App\Filament\Resources\MainBanner3Resource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMainBanner3 extends EditRecord
{
    protected static string $resource = MainBanner3Resource::class;

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
