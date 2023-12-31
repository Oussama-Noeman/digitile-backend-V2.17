<?php

namespace App\Filament\Resources\MailingContactResource\Pages;

use App\Filament\Resources\MailingContactResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMailingContact extends EditRecord
{
    protected static string $resource = MailingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
           DeleteAction::make(),
        ];
    }
}
