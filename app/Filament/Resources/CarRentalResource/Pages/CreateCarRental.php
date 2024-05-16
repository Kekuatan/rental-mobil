<?php

namespace App\Filament\Resources\CarRentalResource\Pages;

use App\Filament\Resources\CarRentalResource;
use App\Models\Car;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCarRental extends CreateRecord
{
    protected static string $resource = CarRentalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        Car::query()->where('id',$data['car_id'] ?? null)
            ->update(['is_available' => 0]);
        $data['status'] = 'ordered';
        return static::getModel()::create($data);
    }

}
