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
    protected $signature = 'app:dukkan-orders-cron {days_limit}';

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
        $daysLimit = $this->argument('days_limit');

        try {
            
            $orderController = app(OrderController::class);
            $orderController->dukkan_orders_update($daysLimit);

            Log::create([
                'level' => 'success',
                'message' => 'Dukkan orders updated successfully with days_limit: ' . $daysLimit,
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