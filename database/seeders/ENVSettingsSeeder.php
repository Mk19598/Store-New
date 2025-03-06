<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL; 
use App\Models\EnvSetting;
use Carbon\Carbon;

class ENVSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnvSetting::truncate();

        $EnvSetting = [
            [  'MAIL_HOST'  => 'email-smtp.ap-south-1.amazonaws.com', 
               'MAIL_PORT'  => '587',
               'MAIL_USERNAME'  => 'AKIA3INYFZ5XLPUBJB7O',
               'MAIL_PASSWORD'  => 'BCLWrO74cHkOno3HGY0CxhSLoCzEDAhky4w7Q7AnDeJI',
               'MAIL_ENCRYPTION'  => 'tls',
               'MAIL_FROM_ADDRESS'  => 'admin@poetsmediagroup.com',
               'MAIL_FROM_NAME'  => 'Standard Oil',
               'ADMIN_MAIL'   => 'admin@poetsmediagroup.com',
               'POETS_API_ACCESS_TOKEN'  => '66ed2f06e9ec1',
               'POETS_API_INSTANCE_ID'  => '66F1315A60C97',
               'Shipping_Username'  => 'vinayagamrajha',
               'Shipping_Password'  => '2b1581466e8b80cb45c0e6334f781595',
               'created_at' => Carbon::now(),
               'updated_at' => null,
            ],
        ];

        EnvSetting::insert($EnvSetting);
    }
}
