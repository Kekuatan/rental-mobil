<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand')->required(),
                Forms\Components\TextInput::make('model')->required(),
                Forms\Components\TextInput::make('license_plate')->required(),
                Forms\Components\TextInput::make('rental_price_per_day')->required(),
                Forms\Components\Toggle::make('is_available')->required(),
                Forms\Components\TextInput::make('created_by')
                    ->default(function(){
                        return Auth::user()->id;
                    })->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('license_plate'),
                Tables\Columns\TextColumn::make('rental_price_per_day')
                    ->numeric(),
                Tables\Columns\ToggleColumn::make('is_available')->disabled(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(function (Tables\Actions\Action $action) {

                    $rowData = $action->getRecord()?? null;

                    if($rowData?? false) {
                        $rawData = $action->getRecord()->toArray();
                        return Auth::user()->id != $rawData['created_by'];
                    }

                    return true;

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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
