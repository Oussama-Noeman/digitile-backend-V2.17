<?php

namespace App\Filament\Resources\MailingContactResource\Pages;

use App\Filament\Resources\MailingContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailingContacts extends ListRecords
{
    protected static string $resource = MailingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
