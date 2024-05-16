<?php

namespace App\Filament\Resources\CarRentalResource\Pages;

use App\Filament\Resources\CarRentalResource;
use App\Models\Car;
use App\Models\CarRental;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCarRental extends EditRecord
{
    protected static string $resource = CarRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $isEdit = $this->form->getRecord()?? null;

        if($isEdit?? false) {
            $recordrRawData = $this->form->getRecord()->toArray();
            if ($recordrRawData['status'] == 'ordered') {
                if($recordrRawData['status'] == 'reject') {
                    Car::where('id', $recordrRawData['car_id'])->update(['is_available' => 1]);
                    CarRental::where('id', $recordrRawData['id'])->delete();
                }
            }
        }


        $record->update($data);
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
