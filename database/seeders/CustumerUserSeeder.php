<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserCrop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustumerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 10,
            'name' => 'Cliente',
            'email' => 'cliente@correo.com',
            'phone_number' => '3050000000',
            'password' => bcrypt('Cliente123'),
            'role' => UserRole::CUSTOMER,
        ]);

        UserCrop::create([
            'user_id' => 10,
            'crop_id' => 1
        ]);
    }
}
