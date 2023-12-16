<?php

namespace App\Filament\Resources\ResGroupResource\Pages;

use App\Filament\Resources\ResGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResGroup extends CreateRecord
{
    protected static string $resource = ResGroupResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create Group');
    }
}
