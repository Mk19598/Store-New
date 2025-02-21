<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        $Userdata = [
            [  
                'name' => 'Standard Store',
                'email' => 'support@standardstore.in',
                'password' => Hash::make('support123!@#'),
                'role' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],

            [  
                'name' => 'Vinay',
                'email' => 'vinay.standardoil@gmail.com',
                'password' => Hash::make('vinay123!@#'),
                'role' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],

            [  
                'name' => 'Radha',
                'email' => 'radha.standardoil@gmail.com',
                'password' => Hash::make('radha123!@#'),
                'role' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],

            [  
                'name' => 'Karunya',
                'email' => 'karunyastandardoil@gmail.com',
                'password' => Hash::make('karunya123!@#'),
                'role' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],

            [  
                'name' => 'Harish',
                'email' => 'harish.standardoil@gmail.com',
                'password' => Hash::make('harish123!@#'),
                'role' => 1,
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
        ];

        User::insert($Userdata);
    }
}