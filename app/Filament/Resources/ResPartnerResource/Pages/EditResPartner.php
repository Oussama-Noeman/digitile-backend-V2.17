<?php

namespace App\Filament\Resources\ResPartnerResource\Pages;

use App\Filament\Resources\ResPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResPartner extends EditRecord
{
    protected static string $resource = ResPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
        // Retrieve the latitude and longitude data from the request data.
        $latt_long = $this->data['latt_long'];
        $latt_long = json_decode($latt_long);

        // Retrieve the current zone record.
        $partner = $this->record;
        if ($latt_long) {
            $partner->update([
                'partner_latitude' => $latt_long->lat,
                'partner_longitude' => $latt_long->lng,
            ]);
        }
    }
    protected function beforeSave(): void
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
    }

    protected function afterFill(): void
    {
        $partner = $this->record;
        if ($partner->is_driver) {
            $this->data['role'] = 'is_driver';
        } else {
            if ($partner->is_chef) {
                $this->data['role'] = 'is_chef';
            } else {
                if ($partner->is_member) {
                    $this->data['role'] = 'is_member';
                } else {
                    if ($partner->is_client) {
                        $this->data['role'] = 'is_client';
                    } else {
                        if ($partner->is_manager) {
                            $this->data['role'] = 'is_manager';
                        }
                    }
                }
            }
        }

        //        dd($this->data);


    }
    public function getTitle(): string 
    {
        return __('Edit Partner');
    }
}
