<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\DukaanOrder;
use App\Models\DukaanBuyer;
use App\Models\DukaanOrderProduct;
use App\Models\WoocommerceOrder;
use App\Models\WoocommerceBuyer;
use App\Models\WoocommerceShipping;
use App\Models\WoocommerceOrderProduct;
use Illuminate\Support\Facades\Log;

class StroageWipeOrdersCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stroage-wipe-orders-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            
            $twoMonthsAgo = Carbon::now()->subMonths(2)->toDateTimeString();
            
            $orders = Order::where('order_created_at', '<', $twoMonthsAgo)->get();
            
            if ($orders->isNotEmpty()) {

                foreach ($orders as $order) {
                    DukaanOrder::where('order_id', $order->order_id)->delete();
                    DukaanBuyer::where('order_id', $order->order_id)->delete();
                    DukaanOrderProduct::where('order_id', $order->order_id)->delete();
                    WoocommerceOrder::where('order_id', $order->order_id)->delete();
                    WoocommerceBuyer::where('order_id', $order->order_id)->delete();
                    WoocommerceShipping::where('order_id', $order->order_id)->delete();
                    WoocommerceOrderProduct::where('order_id', $order->order_id)->delete();
                }
            
                Log::create([
                    'level' => 'success',
                    'message' => 'Orders deleted successfully for records older than 2 months.',
                    'context' => 'storage-wipe-orders-cron',
                ]);
                
            } else {

                Log::create([
                    'level' => 'success',
                    'message' => 'No orders found older than 2 months.',
                    'context' => 'storage-wipe-orders-cron',
                ]);
            }

        } catch (\Exception $e) {
          
            Log::create([
                'level' => 'error',
                'message' => 'Error while deleting the orders: ' . $e->getMessage(),
                'context' => 'stroage-wipe-orders-cron'
            ]);
        }
    }
}