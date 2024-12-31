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
            [  'dukkan_api_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbl90eXBlIjoiYWNjZXNzIiwiZXhwIjoxNzQxNDE0Mzk1LCJqdGkiOiIyYTcwNTFlZmVlNjg0YjBiOThmNWMxODc2NGY3OGNkYyIsInVzZXJfaWQiOjEyNDQzOTA1LCJoYXNfcGFzc3dvcmRfc2V0Ijp0cnVlLCJlbWFpbCI6InN1cHBvcnRAc3RhbmRhcmRzdG9yZS5pbiIsInVzZXJfYWN0aXZlIjp0cnVlLCJ1c2VyX2VtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VybmFtZSI6Iis5MS05Njc3MjI3Njg4IiwiYnV5ZXJfaWQiOjEyMzg1MjQ2LCJzZWxsZXJfaWQiOjc4NDg3MzUsInBpbG90X2lkIjozNTAwNzAxLCJ2ZW5kb3JfaWQiOm51bGwsImJ1eWVyX3V1aWQiOiIxNGU2YTFhZi02YWRmLTQ5OWItOTA2MC1mMDQwYTM2Njk2ODMiLCJzZWxsZXJfdXVpZCI6Ijg4ZmJmZTFkLTBkYjEtNDAyOS04OTQ1LTE5ZjZlYjA3MTRhOSIsInBpbG90X3V1aWQiOiI5NDJjNjIxMS03NWJkLTQ5MDAtYjY1OS1iYzA0ODM3ODg1ZWIiLCJ2ZW5kb3JfdXVpZCI6bnVsbCwidmVuZG9yX2RhdGUiOm51bGwsIm11bHRpcGxlX3N0b3JlcyI6ZmFsc2UsInN0b3JlX3V1aWRzIjpbIjk4N2JhMGFlLTVhNTUtNDczMi04MTQyLWQ4ZDk2MDNiYjc0YSJdLCJzdG9yZV9pZHMiOlsxMDIyNDQ3NTZdLCJzdGFmZl9pZCI6bnVsbCwic3RvcmVfdHlwZSI6MCwiY3VzdG9tX2RhdGEiOnt9fQ.E8eGOQkJWZmvogagOGAY2D7j2PSr42k0xvTbCHPecMk', 
               'dukkan_store_id'  => '987ba0ae-5a55-4732-8142-d8d9603bb74a',
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