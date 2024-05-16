<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'address' => 'Depok',
                'phone_number' => '+6285770648074',
                'driving_license_number' => '1111',
                'password' => Hash::make('admin'),

            ];
        $isExist = User::query()->where('email', $data['email'])->exists();
        if (!$isExist) {
            User::query()->create($data);
        }
    }
}
