<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/private/Products.csv');

        if (!file_exists($filePath)) {
            $this->command->error('CSV file not found: ' . $filePath);
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); 

        $products = [];
        
        while (($row = fgetcsv($file)) !== false) {
            
            if (empty(array_filter($row))) {
                continue;
            }
            $products[] = array_combine($header, $row);
        }
        fclose($file);

        if (!empty($products)) {
            DB::table('products')->insert($products);
            $this->command->info('Product seeding completed successfully!');
        } else {
            $this->command->info('No valid data found to seed.');
        }
    }
}
