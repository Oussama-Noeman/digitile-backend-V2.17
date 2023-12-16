<?php

namespace App\Filament\Resources\CustomerFeedbackResource\Pages;

use App\Filament\Resources\CustomerFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerFeedback extends CreateRecord
{
    protected static string $resource = CustomerFeedbackResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string 
    {
        return __('Create Customer Feedback');
    }


}
