<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryManagement;

class InventoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $filePath = storage_path('app/private/Products.csv');

        if (!file_exists($filePath)) {
            $this->command->error('CSV file not found: ' . $filePath);
            return;
        }

        InventoryManagement::truncate();

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
            InventoryManagement::insert($products);
            InventoryManagement::query()->update(['inventory' => 1, 'status' => 1]);

            $this->command->info('Product seeding completed successfully!');
        } else {
            $this->command->info('No valid data found to seed.');
        }
    }
}
