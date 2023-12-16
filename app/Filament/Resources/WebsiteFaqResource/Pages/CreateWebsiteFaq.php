<?php

namespace App\Filament\Resources\WebsiteFaqResource\Pages;

use App\Filament\Resources\WebsiteFaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteFaq extends CreateRecord
{
    protected static string $resource = WebsiteFaqResource::class;
      public function getTitle(): string 
    {
        return __('Create Website Faq');
    }

}
