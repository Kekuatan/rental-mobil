<?php

namespace App\Filament\Resources\CarReturnResource\Pages;

use App\Filament\Resources\CarReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarReturns extends ListRecords
{
    protected static string $resource = CarReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
