<?php

namespace App\Filament\Resources\CarReturnResource\Pages;

use App\Filament\Resources\CarReturnResource;
use App\Models\Car;
use App\Models\CarRental;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCarReturn extends CreateRecord
{
    protected static string $resource = CarReturnResource::class;

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
        $carRental = CarRental::query()
            ->where('id',$data['car_rental_id'] ?? null)
            ->with('car')
            ->first();
        Car::query()->where('id',$carRental->car->id ?? null)
            ->update(['is_available' => 1]);
        CarRental::query()
            ->where('id',$data['car_rental_id'] ?? null)
            ->with('car')
            ->update(['status' => 'completed']);

        $data = [
            'rental_id' => $data['car_rental_id'],
            'return_date' => $data['return_date'],
            'total_days' => $data['total_day'],
            'total_cost' => $data['total_cost'],
            'created_by' => $data['created_by']
        ];
        return static::getModel()::create($data);
    }
}
