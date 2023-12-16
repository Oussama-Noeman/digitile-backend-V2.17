<?php

namespace App\Filament\Resources\ProductWishlistResource\Pages;

use App\Filament\Resources\ProductWishlistResource;
use Filament\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductWishlist extends EditRecord
{
    protected static string $resource = ProductWishlistResource::class;

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
        return __('Edit Wishlist');
    }
    
}
