<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create User');
    }
    protected function beforeCreate()
    {
        $login = $this->data['login'];
        
        // Check if the login already exists
        $unique = User::where('login', $login)->exists();
     
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
