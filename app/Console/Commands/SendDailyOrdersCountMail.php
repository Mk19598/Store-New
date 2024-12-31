<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyOrdersCountMail;
use App\Helpers\CustomHelper;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        // $ordersQuery = Order::query(); 

        // $orders_today = $ordersQuery->whereDate('order_created_at', Carbon::now()->toDateString())->get();

        // $data = [
        //     'orders_today' => $orders_today,
        //     'orders_count' => $orders_today->count(), 
        //     'dukkan_orders_count' => $orders_today->where('order_vai', 'Dukkan')->count(), 
        //     'woocommerce_orders_count' => $orders_today->where('order_vai', 'woocommerce')->count(),
        //     'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
        //     'Get_website_name' => CustomHelper::Get_website_name(),
        // ];
        
        // Mail::to(CustomHelper::Get_ADMIN_MAIL())->send(new DailyOrdersCountMail($data));

       DB::table('user_roles')->insert(['role_name' => 1]);

        $this->info('Daily email sent successfully!');
    }
}