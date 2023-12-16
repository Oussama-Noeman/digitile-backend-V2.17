<?php

namespace App\Filament\Resources\ResPartnerResource\Pages;

use App\Filament\Resources\ResPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResPartner extends CreateRecord
{
    protected static string $resource = ResPartnerResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    protected function afterCreate(): void
    {
        $role = $this->data['role'];

        $partner = $this->record;
        $partner->is_manager = 0;
        $partner->is_client = 0;
        $partner->is_driver = 0;
        $partner->is_member = 0;
        $partner->is_chef = 0;
        $partner->$role = 1;
        if ($role != 'is_chef') {
            $partner->kitchen_id = null;
        }
        $partner->save();
        //        dd($role);

        $this->data[$role] = true;
    }
    public function getTitle(): string 
   {
       return __('Create Partner');
   }
}
