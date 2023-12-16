<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Edit User');
    }
    protected function beforeSave()
    {
        $login = $this->data['login'];
        
        // Check if the login already exists
        $unique = User::where('login', $login)
        ->where('id','!=',$this->record->id)
        ->exists();
     
        if ($unique) {
            Notification::make()
                ->warning()
                ->title('Login must be unique')
                ->body('The provided login already exists. Please choose a different login.')
                ->persistent()
                ->send();
            
            $this->halt(); // Stop the creation process
        }
    }
}
