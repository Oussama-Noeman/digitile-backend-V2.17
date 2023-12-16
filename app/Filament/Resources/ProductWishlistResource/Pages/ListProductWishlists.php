<?php

namespace App\Filament\Resources\ProductWishlistResource\Pages;

use App\Filament\Resources\ProductWishlistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductWishlists extends ListRecords
{
    protected static string $resource = ProductWishlistResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
