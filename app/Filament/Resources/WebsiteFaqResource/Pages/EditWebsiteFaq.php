<?php

namespace App\Filament\Resources\WebsiteFaqResource\Pages;

use App\Filament\Resources\WebsiteFaqResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteFaq extends EditRecord
{
    protected static string $resource = WebsiteFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getTitle(): string 
    {
        return __('Edit Website Faq');
    }
}
