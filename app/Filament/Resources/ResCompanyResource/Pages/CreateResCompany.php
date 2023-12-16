<?php

namespace App\Filament\Resources\ResCompanyResource\Pages;

use App\Filament\Resources\ResCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResCompany extends CreateRecord
{
    protected static string $resource = ResCompanyResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string 
    {
        return __('Create Company');
    }
}
