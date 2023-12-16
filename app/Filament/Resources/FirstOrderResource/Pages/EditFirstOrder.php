<?php

namespace App\Filament\Resources\FirstOrderResource\Pages;

use App\Filament\Resources\FirstOrderResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFirstOrder extends EditRecord
{
    protected static string $resource = FirstOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $template = $this->record;
        $attributes = $this->data['type'];
        if($attributes=="3"){
            $template->amount=0;
        }
        // dd($template->amount);
        if($attributes=="1" && $this->data['amount']>100 ){
            Notification::make()
            ->warning()
            ->title('Percentage Error')
            ->body('The percentage cannot be greater than 100.')
            ->persistent()
            ->send();

        $this->halt();
        }

    }
}
