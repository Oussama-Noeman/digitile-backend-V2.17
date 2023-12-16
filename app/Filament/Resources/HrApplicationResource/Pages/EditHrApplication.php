<?php

namespace App\Filament\Resources\HrApplicationResource\Pages;

use App\Filament\Resources\HrApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHrApplication extends EditRecord
{
    protected static string $resource = HrApplicationResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }
}
