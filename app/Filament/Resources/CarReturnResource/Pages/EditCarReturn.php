<?php

namespace App\Filament\Resources\CarReturnResource\Pages;

use App\Filament\Resources\CarReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarReturn extends EditRecord
{
    protected static string $resource = CarReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
