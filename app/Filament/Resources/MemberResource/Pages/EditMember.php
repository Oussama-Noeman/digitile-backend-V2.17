<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getHeaderActions(): array
    {
        return [
          DeleteAction::make(),
        ];
    }
    public function getTitle(): string 
    {
        return __('Edit Member');
    }
}
