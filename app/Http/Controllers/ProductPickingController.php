<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use App\Helpers\CustomHelper;
use App\Models\WoocommerceProduct;
use App\Models\DukaanProduct;
use App\Models\Order;

class ProductPickingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = array(
                'title' => "Product Pickup | ". CustomHelper::Get_website_name() ,
                'today' => Carbon::today()->format('Y-m-d') ,
                'query' => [],
            );
    
            return view('products.warehouse.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }
    }

    public function filter(Request $request) {


        $dukaanProductQuery = DukaanProduct::query()->select('orders.status','dukaan_products.*', DB::raw('SUM(quantity) as total_quantity'))
                                                ->join('orders','orders.order_uuid','=','dukaan_products.order_uuid');

        $woocommerceProductQuery = WoocommerceProduct::query()->select('orders.status','woocommerce_products.*', DB::raw('SUM(quantity) as total_quantity'))
                                                ->join('orders','orders.order_uuid','=','woocommerce_products.order_uuid');

        
        $this->applyFilters($dukaanProductQuery, $request);
        $this->applyFilters($woocommerceProductQuery, $request);
        
        $dukaanProducts = $dukaanProductQuery->groupBy('product_id')->get();
    
        $woocommerceProducts = $woocommerceProductQuery->groupBy('product_id')->get();
        
        $query = $dukaanProducts->concat($woocommerceProducts);

        $data = [
            'title' =>  "Product Pickup | " .CustomHelper::Get_website_name() ,
            'today' => Carbon::today()->format('Y-m-d') ,
            'query' => $query,
        ];
    
        return view('products.warehouse.index-table', $data)->render();
    }
    
    private function applyFilters($query, $request) {

        if ($request->filled('date_from')) {
            $query->whereDate('orders.order_created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('orders.order_created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('order_vai')) {
            $query->where('orders.order_vai', $request->order_vai);
        }
        
        if ($request->filled('order_id')) {
            $query->where('orders.order_id', $request->order_id);
        }

        //From Orders

        if ($request->filled('status') && $request->status !== 'all') {

            $statusMap = [
                'pending'    => ['pending', '0'],
                'processing' => ['processing'],
                'completed'  => ['completed', 5],
                'cancelled'  => ['cancelled', 4, 7 ,7,-1],
                'failed'     => ['failed', 6],
                'refunded'   => ['refunded', 10],
                'shipped'    => [ 'order-shipped'],  
            ];
        
            if (isset($statusMap[$request->status])) {
                $query->whereIn('orders.status', $statusMap[$request->status]);
            }else{
                $query->where('orders.status', $request->status);
            }
        }
    }
}