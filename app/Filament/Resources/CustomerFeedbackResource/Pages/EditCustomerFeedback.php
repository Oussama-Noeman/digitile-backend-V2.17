<?php

namespace App\Filament\Resources\CustomerFeedbackResource\Pages;

use App\Filament\Resources\CustomerFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerFeedback extends EditRecord
{
    protected static string $resource = CustomerFeedbackResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\ViewAction::make(),
    //         Actions\DeleteAction::make(),
    //     ];
    // }
    public function getTitle(): string 
    {
        return __('Edit Customer Feedback');
    }

}
