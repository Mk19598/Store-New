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
            [  'dukkan_api_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbl90eXBlIjoiYWNjZXNzIiwiZXhwIjoxNzQ5NDgzMzMxLCJqdGkiOiJiODVkYmM1NTExOWE0MzBmYWQyYzJiZjI1NjhjYWM1ZCIsInVzZXJfaWQiOjEzODExODkyLCJoYXNfcGFzc3dvcmRfc2V0Ijp0cnVlLCJlbWFpbCI6InBvZXRzbWVkaWFncm91cEBnbWFpbC5jb20iLCJ1c2VyX2FjdGl2ZSI6dHJ1ZSwidXNlcl9lbWFpbF92ZXJpZmllZCI6dHJ1ZSwidXNlcm5hbWUiOiJwb2V0c21lZGlhZ3JvdXBAZ21haWwuY29tIiwiYnV5ZXJfaWQiOjEzNzQyMTI5LCJzZWxsZXJfaWQiOjgxMzc1MjgsInBpbG90X2lkIjozNzk1MDAzLCJ2ZW5kb3JfaWQiOm51bGwsImJ1eWVyX3V1aWQiOiJjYThjMzE2Ni05ZjU2LTRkOGItYTdiMi05YWY3NDlhODhkZWQiLCJzZWxsZXJfdXVpZCI6IjJjYjY2N2ZlLWViOWUtNGIwMS1hZjMwLWZhOTk1ODQ3YjRiYiIsInBpbG90X3V1aWQiOiJlZmY5NTAyYS1mZGNjLTQ4YmMtOTM0Yy04M2VkYThlZTE2MGIiLCJ2ZW5kb3JfdXVpZCI6bnVsbCwidmVuZG9yX2RhdGUiOm51bGwsIm11bHRpcGxlX3N0b3JlcyI6ZmFsc2UsInN0b3JlX3V1aWRzIjpbIjI4YWFlOGMxLTZhMjAtNDlkNS04NTVhLTExYzhmODIyMzE2YiJdLCJzdG9yZV9pZHMiOlsxMDI5MzAyNTRdLCJzdGFmZl9pZCI6OTYyMiwic3RvcmVfdHlwZSI6MCwiY3VzdG9tX2RhdGEiOnt9fQ.7C7gGbmTP3kP7B8TBpZigFezBt8tWT85fJkZ9eecyvg', 
               'dukkan_store_id'  => '28aae8c1-6a20-49d5-855a-11c8f822316b',
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