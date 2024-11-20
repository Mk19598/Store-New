<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use App\Helpers\CustomHelper;
use Carbon\Carbon;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        try {
                // Status
                
            $statusMap = [
                'pending'    => ['pending','on-hold', '0'],  
                'completed'  => ['completed', '1', '5'],  
                'cancelled'  => ['cancelled', '2', '4', '7','-1'],  
                'failed'     => ['failed', '6'],  
                'refunded'   => ['refunded', '10'], 
                'processing' => ['processing', '3'],  
                'shipped'    => [ '3'],  
                'default'    => ['default']
            ];

            $statusCounts = collect($statusMap)->mapWithKeys(function ($statusValues, $statusName) {
                return [$statusName => Order::whereIn('status', $statusValues)->count()];
            })->toArray();

                // Orders Chart

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            
            $ordersWeek = Order::query()
                ->whereBetween('order_created_at', [$startOfWeek, $endOfWeek])
                ->get()
                ->groupBy(function ($order) {
                    return Carbon::parse($order->order_created_at)->format('l'); 
                });
            
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            $daysOfWeekCounts = [];
            
            foreach ($days as $day) {
                $dailyOrders = $ordersWeek->get($day, collect());
            
                $daysOfWeekCounts[$day] = [
                    'order_count' => $dailyOrders->count(),
                    'woocommerce_order_count' => $dailyOrders->where('order_vai', 'woocommerce')->count(),
                    'Dukkan_order_count' => $dailyOrders->where('order_vai', 'Dukkan')->count(),
                ];
            }
                
            $orders = Order::query();

            $data = array( 'title'  => "Dashboard | ".CustomHelper::Get_website_name()  ,
                            'order_count' => $orders->count(),
                            'Dukkan_order_count' => $orders->where('order_vai','Dukkan')->count(),
                            'woocommerce_order_count' =>  Order::query()->where('order_vai','woocommerce')->count(),
                            'today' => Carbon::today()->format('Y-m-d') ,
                            'statusCounts' => $statusCounts ,
                            'daysOfWeekCounts' => $daysOfWeekCounts,
                        );

            return view('dashboard.home', $data);
        
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return view('layouts.404-Page');
        }
    }
}
