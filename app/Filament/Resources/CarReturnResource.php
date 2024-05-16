<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarReturnResource\Pages;
use App\Filament\Resources\CarReturnResource\RelationManagers;
use App\Models\CarRental;
use App\Models\CarReturn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class CarReturnResource extends Resource
{
    protected static ?string $model = CarReturn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rental_id')->options(function(){
                    return  CarRental::select('id', 'car_id', 'start_date', 'end_date')
                        ->where('status', 'ongoing')
                        ->get()
                        ->mapWithKeys(function ($car) {
                            return [$car->id => $car->car->brand . ' - '.$car->car->model . ' - '.$car->car->license_plate ];
                        })->toArray();
                })->live()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set){
                        $rentalCarId = $state;
                        $rentalCar = CarRental::where('id', $rentalCarId)->with('car')->first();

                        $startDate = Carbon::parse($rentalCar->start_date);
                        $endDate = Carbon::parse($rentalCar->end_date);
                        $returnDate = Carbon::parse($get('return_date'));
                        $diffDay = $startDate->diffInDays($endDate);
                        $diffDayActual = $startDate->diffInDays($returnDate);

                        $set('start_date', $rentalCar->start_date);
                        $set('end_date', $rentalCar->end_date);
                        $set('schedule_day', $diffDay);
                        $set('total_day', $diffDayActual);
                        $set('total_cost', $rentalCar->car->rental_price_per_day * $diffDayActual);
                    })
                    ->required(),
                Forms\Components\DatePicker::make('return_date')
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set){
                        $rentalCarId = $get('rental_id');
                        $rentalCar = CarRental::where('id', $rentalCarId)->with('car')->first();

                        $startDate = Carbon::parse($rentalCar->start_date);
                        $endDate = Carbon::parse($rentalCar->end_date);
                        $returnDate = Carbon::parse($state);
                        $diffDayActual = $startDate->diffInDays($returnDate);

                        $diffDay = $startDate->diffInDays($endDate);
                        $set('start_date', $rentalCar->start_date);
                        $set('end_date', $rentalCar->end_date);
                        $set('schedule_day', $diffDay);
                        $set('total_day', $diffDayActual);
                        $set('total_cost', $rentalCar->car->rental_price_per_day * $diffDayActual);
                    })
                    ->default(now())
                    ->live()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')->disabled(),
                Forms\Components\DatePicker::make('end_date')->disabled(),
                Forms\Components\TextInput::make('schedule_day')->label('Schedule day'),
                Forms\Components\TextInput::make('total_day')->label('Actual day'),
                Forms\Components\TextInput::make('total_cost'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car_rental_id.car.brand')->label('Car Brand'),
                Tables\Columns\TextColumn::make('car_rental_id.car.model')->label('Car Model'),
                Tables\Columns\TextColumn::make('car_rental_id.car.license_plate')->label('Car Plate'),
                Tables\Columns\TextColumn::make('return_date'),
                Tables\Columns\TextColumn::make('total_days'),
                Tables\Columns\TextColumn::make('total_cost'),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCarReturns::route('/'),
            'create' => Pages\CreateCarReturn::route('/create'),
            'edit' => Pages\EditCarReturn::route('/{record}/edit'),
        ];
    }
}
