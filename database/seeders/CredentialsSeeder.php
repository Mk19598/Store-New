<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Credentials;
use Carbon\Carbon;

class CredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Credentials::truncate();
        
        $Credentials_inputs = [
            [  'dukkan_api_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbl90eXBlIjoiYWNjZXNzIiwiZXhwIjoxNzQxMTEyOTY4LCJqdGkiOiI0YTE1NTBiMmEzODk0Y2NhYjVjZmViMjc5MGQ1NGE5ZSIsInVzZXJfaWQiOjEzNzM4ODY1LCJoYXNfcGFzc3dvcmRfc2V0Ijp0cnVlLCJlbWFpbCI6ImR1a2FhbkBtYWlsaW5nMjQ3LmNvbSIsInVzZXJfYWN0aXZlIjp0cnVlLCJ1c2VyX2VtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VybmFtZSI6ImR1a2FhbkB2YXVsdGQuZGUiLCJidXllcl9pZCI6MTM2NjkyNzYsInNlbGxlcl9pZCI6ODExOTU3NiwicGlsb3RfaWQiOjM3NzY3MzAsInZlbmRvcl9pZCI6bnVsbCwiYnV5ZXJfdXVpZCI6ImUxNjI2NzVmLWYxMmMtNDZiMC04MTRiLTQxMTdlODUxODc1ZiIsInNlbGxlcl91dWlkIjoiODNjNzVkZTUtOTVjZS00ZTJmLTk1NTYtOGQ3YTQxNzM1Njk1IiwicGlsb3RfdXVpZCI6ImE0MTc1MjcwLTdlOWItNGE0Yi05ZDNiLTE1ODJhOTRiZDJkMyIsInZlbmRvcl91dWlkIjpudWxsLCJ2ZW5kb3JfZGF0ZSI6bnVsbCwibXVsdGlwbGVfc3RvcmVzIjpmYWxzZSwic3RvcmVfdXVpZHMiOlsiMjYzODk4MmMtNTgzMi00YWM0LTk4MGUtZTYwNTYxYTU1ZWY4Il0sInN0b3JlX2lkcyI6WzEwMjY4NTU1MF0sInN0YWZmX2lkIjpudWxsLCJzdG9yZV90eXBlIjowLCJjdXN0b21fZGF0YSI6e319.35mVgOBtf8bGQdeVihIUAIlm0NO52Mm-MaOISg4gTrE', 
               'woocommerce_url'  => 'https://standardcoldpressedoil.com',
               'woocommerce_customer_key' => 'ck_095510b173e2a3deb35f26e1a403f22d7ee2c60f',
               'woocommerce_secret_key'   =>  'cs_3865feb56d3de76b3eb3a6c865593b687f284488',
               'created_at' => Carbon::now(),
               'updated_at' => null,
            ],
        ];

        Credentials::insert($Credentials_inputs);
    }
}