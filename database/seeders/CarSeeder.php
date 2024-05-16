<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $cars = [
            [
                'brand' => 'Mitsubisi',
                'model' => 'M-A1',
                'license_plate' => 'B '.fake()->numberBetween(10000, 99999).' UX',
                'rental_price_per_day' => 10000,
                'is_available' => true,
            ],
            [
                'brand' => 'Xenia',
                'model' => 'X-A1',
                'license_plate' => 'B '.fake()->numberBetween(10000, 99999).' UX',
                'rental_price_per_day' => 10000,
                'is_available' => true,
            ],
            [
                'brand' => 'Mitsubisi',
                'model' => 'M-A2',
                'license_plate' => 'B '.fake()->numberBetween(10000, 99999).' UX',
                'rental_price_per_day' => 10000,
                'is_available' => true,
            ],
            [
                'brand' => 'Kijang',
                'model' => 'K-A1',
                'license_plate' => 'B '.fake()->numberBetween(10000, 99999).' UX',
                'rental_price_per_day' => 10000,
                'is_available' => true,
            ]
        ];

        foreach ($cars as $car) {
            $isExist = Car::query()->where('license_plate', $car['license_plate'])->exists();
            if (!$isExist) {
                $car['created_by'] = User::query()->first()->id ?? null;
                Car::query()->create($car);
            }

        }
    }
}
