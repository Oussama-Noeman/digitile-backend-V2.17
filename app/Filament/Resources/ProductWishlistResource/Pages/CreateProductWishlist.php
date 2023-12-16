<?php

namespace App\Filament\Resources\ProductWishlistResource\Pages;

use App\Filament\Resources\ProductWishlistResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductWishlist extends CreateRecord
{
    protected static string $resource = ProductWishlistResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    public function getTitle(): string 
    {
        return __('Create Wishlist');
    }
}
