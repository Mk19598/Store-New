<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderController;
use App\Models\Log;

class WoocommerenceOrdersCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:woocommerence-orders-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Everday Woocommerence orders updating';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            
            $orderController = app(OrderController::class);
            $orderController->woocommerce_orders_update();

            Log::create([
                'level' => 'success',
                'message' => 'Woocommerence orders updated successfully',
                'context' => 'Woocommerence-orders-cron'
            ]);

        } catch (\Exception $e) {
          
            Log::create([
                'level' => 'error',
                'message' => 'Error updating Woocommerence orders: ' . $e->getMessage(),
                'context' => 'Woocommerence-orders-cron'
            ]);
        }
    }
}
