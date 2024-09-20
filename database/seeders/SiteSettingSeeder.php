<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL; 
use App\Models\SiteSetting;
use Carbon\Carbon;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::truncate();

        $SiteSetting = [
            [  'website_name'  => 'Store', 
               'website_logo'  => 'Standard-store-logo.webp',
               'created_at' => Carbon::now(),
               'updated_at' => null,
            ],
        ];

        SiteSetting::insert($SiteSetting);
    }
}
