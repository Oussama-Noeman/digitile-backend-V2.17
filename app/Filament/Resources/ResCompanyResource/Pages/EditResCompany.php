<?php

namespace App\Filament\Resources\ResCompanyResource\Pages;

use App\Filament\Resources\ResCompanyResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResCompany extends EditRecord
{
    protected static string $resource = ResCompanyResource::class;

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
        return __('Edit Company');
    }
}
