<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyOrdersCountMail;
use App\Helpers\CustomHelper;
use App\Models\Order;
use App\Models\Log;
use Carbon\Carbon;

class SendDailyOrdersCountMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily-orders-count';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Send a daily orders received email at 11:00 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
         
            $ordersQuery = Order::query(); 

            $orders_today = $ordersQuery->whereDate('order_created_at', Carbon::now()->toDateString())->get()
                                        ->map(function($item){

                                            $statusMap = [
                                                'pending'       => 'pending',
                                                'completed'     => 'completed',
                                                'cancelled'     => 'cancelled',
                                                'failed'        => 'cancelled',
                                                'refunded'      => 'refunded',
                                                'processing'    => 'processing',
                                                'order-shipped' => 'shipped',
                                                'Packed'        => 'Packed',
                                                -1              => 'ABANDONED / DRAFT',
                                                0               => 'pending',
                                                1               => 'Accepted',
                                                2               => 'Rejected',
                                                3               => 'shipped',
                                                4               => 'cancelled',
                                                5               => 'completed',
                                                6               => 'cancelled',
                                                7               => 'cancelled',
                                                10              => 'refunded',
                                            ];
                                    
                                            $current_status = $statusMap[$item->status] ?? null;
                                            
                                            $item->current_status = $current_status;

                                            return $item;
                                        });

            $status_counts = $orders_today->groupBy('current_status')->map(fn($group) => $group->count());

            $status_counts = $status_counts->toArray();

            $data = [
                'orders_today' => $orders_today,
                'orders_count' => $orders_today->count(), 
                'dukkan_orders_count' => $orders_today->where('order_vai', 'Dukkan')->count(), 
                'woocommerce_orders_count' => $orders_today->where('order_vai', 'woocommerce')->count(),
                'status_counts'    => $status_counts ,
                'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
                'Get_website_name' => CustomHelper::Get_website_name(),
            ];
            
            Mail::to(CustomHelper::Get_ADMIN_MAIL())->send(new DailyOrdersCountMail($data));

            Log::create([
                'level' => 'success',
                'message' => 'Send a daily orders received email at 11:00 PM',
                'context' => 'email:daily-orders-count'
            ]);

        } catch (\Throwable $th) {

            Log::create([
                'level' => 'error',
                'message' => 'Error updating Woocommerence orders: ' . $th->getMessage(),
                'context' => 'email:daily-orders-count'
            ]);
        }
    }
}