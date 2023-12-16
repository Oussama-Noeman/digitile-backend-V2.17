<?php

namespace App\Filament\Resources\ResCurrencyResource\Pages;

use App\Filament\Resources\ResCurrencyResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResCurrency extends EditRecord
{
    protected static string $resource = ResCurrencyResource::class;

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
        return __('Edit Currency');
    }
}
