<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderController;
use App\Models\Log;

class DukkanOrdersCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dukkan-orders-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Everyday Dukkan orders updating';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            
            $orderController = app(OrderController::class);
            $orderController->dukkan_orders_update();

            Log::create([
                'level' => 'success',
                'message' => 'Dukkan orders updated successfully' ,
                'context' => 'dukkan-orders-cron'
            ]);

        } catch (\Exception $e) {
          
            Log::create([
                'level' => 'error',
                'message' => 'Error updating Dukkan orders: ' . $e->getMessage(),
                'context' => 'dukkan-orders-cron'
            ]);

        }
    }
}