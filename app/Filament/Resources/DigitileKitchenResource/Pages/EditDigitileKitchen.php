<?php

namespace App\Filament\Resources\DigitileKitchenResource\Pages;

use App\Filament\Resources\DigitileKitchenResource;
use App\Models\DigitileKitchen;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDigitileKitchen extends EditRecord
{
    protected static string $resource = DigitileKitchenResource::class;

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
        $default=$this->data['is_default'];
        if($default){
            DigitileKitchen::where('id', '<>', $this->record->id)->update(['is_default' => false]);  
        }
        if (DigitileKitchen::where('is_default', true)->doesntExist()) {
            Notification::make()
            ->warning()
            ->title('Default false')
            ->body('There must be at least one default kitchen.')
            ->persistent()
            ->send();
        $this->halt(); 

        }
    }

}
