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

class ProductController extends Controller
{
    public function warehouse_pickings(Request $request)
    {
        try {
            $data = array(
                'title'  => CustomHelper::Get_website_name(). " | Product Pickup" ,
                'query' => [],
            );
    
            return view('products.warehouse.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }
    }

    public function warehouse_picking_products(Request $request) {


        $dukaanProductQuery = DukaanProduct::query();
        $woocommerceProductQuery = WoocommerceProduct::query();
        
        $this->applyFilters($dukaanProductQuery, $request);
        $this->applyFilters($woocommerceProductQuery, $request);
        
        $dukaanProducts = $dukaanProductQuery
            ->select('dukaan_products.*', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->get();
    
        $woocommerceProducts = $woocommerceProductQuery
            ->select('woocommerce_products.*', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->get();
        
        $query = $dukaanProducts->concat($woocommerceProducts);
        
        $data = [
            'title' => CustomHelper::Get_website_name() . " | Product Pickup",
            'query' => $query,
        ];
    
        return view('products.warehouse.index-table', $data)->render();
    }
    
    private function applyFilters($query, $request) {

        if ($request->filled('date_from')) {
            $query->whereDate('order_created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('order_created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('order_vai')) {
            $query->where('order_vai', $request->order_vai);
        }
        
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
    }
}