<?php

namespace App\Filament\Resources\CarRentalResource\Pages;

use App\Filament\Resources\CarRentalResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCarRentals extends ListRecords
{
    protected static string $resource = CarRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
            'My order car' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->id())),
            'My rental car' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('car', fn (Builder $query) => $query->where('created_by', auth()->id()))),
        ];
    }
}
