<?php

namespace App\Filament\Resources\MainBanner2Resource\Pages;

use App\Filament\Resources\MainBanner2Resource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMainBanner2 extends EditRecord
{
    protected static string $resource = MainBanner2Resource::class;

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
