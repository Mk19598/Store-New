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
        $this->call([
            UserSeeder::class,
            SiteSettingSeeder::class,
            CredentialsSeeder::class,
            ContentTemplateSeeder::class,
            InventoryProductSeeder::class,
            ENVSettingsSeeder::class
        ]);
    }
}
