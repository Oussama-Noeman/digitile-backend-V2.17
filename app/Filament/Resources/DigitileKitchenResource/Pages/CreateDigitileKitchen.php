<?php

namespace App\Filament\Resources\DigitileKitchenResource\Pages;

use App\Filament\Resources\DigitileKitchenResource;
use App\Models\DigitileKitchen;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDigitileKitchen extends CreateRecord
{
    protected static string $resource = DigitileKitchenResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        $default=$this->data['is_default'];
        if($default){
            DigitileKitchen::where('id', '<>', $this->record->id)->update(['is_default' => false]);  
        }
        if (DigitileKitchen::where('is_default', true)->doesntExist()) {
            DigitileKitchen::where('id',  $this->record->id)->update(['is_default' => true]);  

        }
    }

}
