<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarRentalResource\Pages;
use App\Filament\Resources\CarRentalResource\RelationManagers;
use App\Models\Car;
use App\Models\CarRental;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarRentalResource extends Resource
{
    protected static ?string $model = CarRental::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {


        $isEdit = $form->getRecord()?? null;

        if($isEdit?? false){
            $record = $form->getRecord()->toArray();
            if($record['status'] == 'ordered'){
                return $form
                    ->schema([
                        Forms\Components\Select::make('car_id')
                            ->options(function (){
                                return  Car::select('id', 'brand', 'model')
                                    ->get()
                                    ->mapWithKeys(function ($car) {
                                        return [$car->id => $car->brand . ' - ' . $car->model];
                                    })->toArray();
                            })->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('approve')
                            ->options(function (){
                                return  ['ongoing' => 'Approve', 'Reject' => 'reject'];
                            }),
                        Forms\Components\DatePicker::make('start_date')->disabled(),
                        Forms\Components\DatePicker::make('end_date')->disabled(),
                    ]);
            }

            if($record['status'] == 'ongoing'){
                return $form
                    ->schema([
                        Forms\Components\Select::make('car_id')
                            ->options(function (){
                                return  Car::select('id', 'brand', 'model')
                                    ->get()
                                    ->mapWithKeys(function ($car) {
                                        return [$car->id => $car->brand . ' - ' . $car->model];
                                    })->toArray();
                            })->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('approve')
                            ->options(function (){
                                return  ['ongoing' => 'Approve', 'Reject' => 'reject'];
                            })->disabled(),
                        Forms\Components\DatePicker::make('start_date')->disabled(),
                        Forms\Components\DatePicker::make('end_date')->disabled(),
                    ]);
            }
        }

        return $form
            ->schema([
                Forms\Components\Select::make('car_id')
                    ->options(function (){
                        return  Car::select('id', 'brand', 'model')
                            ->where('is_available', 1)
                            ->get()
                            ->mapWithKeys(function ($car) {
                            return [$car->id => $car->brand . ' - ' . $car->model];
                        })->toArray();
                    })->searchable(),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdBy.name'),
                Tables\Columns\TextColumn::make('car.brand')->label('Car Brand'),
                Tables\Columns\TextColumn::make('car.model')->label('Car Model'),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(function ($record) {
                    $record = $record->toArray();
                    if($record['status'] == 'ordered'){
                        return false;
                    } else{
                        return true;
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarRentals::route('/'),
            'create' => Pages\CreateCarRental::route('/create'),
            'edit' => Pages\EditCarRental::route('/{record}/edit'),
        ];
    }
}
