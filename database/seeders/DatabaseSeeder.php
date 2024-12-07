<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL; 
use Carbon\Carbon;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Manikandan',
            'email' => 'manikandan@gmail.com',
            'password' => Hash::make(1234),
            'role' => 1,
            'active' => 1,
        ]);

        $this->call([
            SiteSettingSeeder::class,
            CredentialsSeeder::class,
            ContentTemplateSeeder::class,
        ]);
    }
}
