<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use App\Helpers\CustomHelper;
use App\Models\WoocommerceOrderProduct;
use App\Models\DukaanOrderProduct;
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
    
            return view('products-picking.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.error-pages.404-Page');
        }
    }

    public function filter(Request $request) {


        $DukaanOrderProductQuery = DukaanOrderProduct::query()->select('orders.status', 'dukaan_order_products.*', DB::raw('SUM(quantity) as total_quantity'))
                            ->join('orders', 'orders.order_id', '=', 'dukaan_order_products.order_id');

        $WoocommerceOrderProductQuery = WoocommerceOrderProduct::query()->select('orders.status', 'woocommerce_order_products.*', DB::raw('SUM(quantity) as total_quantity'))
            ->join('orders', 'orders.order_id', '=', 'woocommerce_order_products.order_id');

        $this->applyFilters($DukaanOrderProductQuery, $request);
        $this->applyFilters($WoocommerceOrderProductQuery, $request);

        $DukaanOrderProducts = $DukaanOrderProductQuery->groupBy('sku')->get()->map(function ($item) {
            $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');
            $item['sku'] = $item->sku;
            return $item;
        });

        $WoocommerceOrderProducts = $WoocommerceOrderProductQuery->groupBy('sku')->get()->map(function ($item) {
            $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');
            $item['sku'] = $item->sku;
            return $item;
        });

        $combinedProducts = $DukaanOrderProducts->concat($WoocommerceOrderProducts);
        
        $groupedProducts = $combinedProducts->groupBy('sku')->map(function ($group) {
            $firstItem = $group->first(); // Get the first item in the group to retain other fields
            $totalQuantity = $group->sum('total_quantity'); // Sum the quantities
        
            // Collect unique dates and skip duplicates
            $dates = [];
            foreach ($group as $item) {
                $date = $item['order_created_at_format'];
                if (!in_array($date, $dates)) {
                    $dates[] = $date;
                }
            }
        
            // Concatenate dates with a comma
            $firstItem['order_dates'] = implode(', ', $dates);
            $firstItem['total_quantity'] = $totalQuantity;
        
            return $firstItem;
        });
        
        // Convert the grouped collection back to a plain collection
        $query = $groupedProducts->values();
        

        $data = [
            'title' =>  "Product Pickup | " .CustomHelper::Get_website_name() ,
            'today' => Carbon::today()->format('Y-m-d') ,
            'query' => $query,
        ];
    
        return view('products-picking.index-table', $data)->render();
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

        $query->OrderBy('orders.order_created_at');

        //From Orders

        // if ($request->filled('status') && $request->status !== 'all') {

        //     $statusMap = [
        //         'pending'    => ['pending', '0'],
        //         'processing' => ['processing'],
        //         'completed'  => ['completed', 5],
        //         'cancelled'  => ['cancelled', 4, 7 ,7,-1],
        //         'failed'     => ['failed', 6],
        //         'refunded'   => ['refunded', 10],
        //         'shipped'    => [ 'order-shipped'], 
        //         'Packed'     => [ 'Packed'],
        //     ];
        
        //     if (isset($statusMap[$request->status])) {
        //         $query->whereIn('orders.status', $statusMap[$request->status]);
        //     }else{
        //         $query->where('orders.status', $request->status);
        //     }
        // }

        if ($request->filled('status') && !in_array('all', $request->status)) {
            $statusMap = [
                'pending'    => ['pending', '0'],
                'processing' => ['processing'],
                'completed'  => ['completed', 5],
                'cancelled'  => ['cancelled', 4, 7, -1],
                'failed'     => ['failed', 6],
                'refunded'   => ['refunded', 10],
                'shipped'    => ['order-shipped'], 
                'packed'     => ['Packed'],
            ];
        
            $mappedStatuses = [];
            foreach ($request->status as $status) {
                if (isset($statusMap[$status])) {
                    $mappedStatuses = array_merge($mappedStatuses, $statusMap[$status]);
                } else {
                    $mappedStatuses[] = $status;
                }
            }
        
            $mappedStatuses = array_unique($mappedStatuses);
        
            $query->whereIn('orders.status', $mappedStatuses);
        }
        
    }
}