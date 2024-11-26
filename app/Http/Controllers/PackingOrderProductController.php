<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Models\WoocommerceOrderProduct;
use App\Models\DukaanOrderProduct;
use App\Models\Order;

class PackingOrderProductController extends Controller
{
    public function index(Request $request)
    {
        try {
        
            $orders_collection = null;
        
            if ($request->ajax()) {

                $orders_collection = Order::query()->where('order_id', $request->order_id)->get()->map(function ($item) {

                    $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');
        
                    if ($item->order_vai == "Dukkan") {
                        
                        $DukaanOrderProducts = DukaanOrderProduct::where('order_uuid', $item->order_uuid)->get();
        
                        $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');
        
                        $item['product_details'] = $DukaanOrderProducts->map(function ($item) use ($totalCostSum) {

                            $item['product_name'] = $item->product_slug;
                            $item['sku_id'] = $item->product_sku_id;
                            $item['product_total_cost'] = $item->line_item_total_cost;
                            $item['price'] = $item->selling_price;
                            $item['discount'] = $item->line_item_discount;
                            $item['sum_total_cost'] = $totalCostSum;
                            return $item;
                        });
                    }
        
                    if ($item->order_vai == "woocommerce") {

                        $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_uuid', $item->order_uuid)->get();
        
                        $totalCostSum = $WoocommerceOrderProduct->sum('total');
        
                        $item['product_details'] = $WoocommerceOrderProduct->map(function ($item) use ($totalCostSum) {

                            $item['product_name'] = $item->name;
                            $item['sku_id'] = $item->sku;
                            $item['product_total_cost'] = $item->total;
                            $item['price'] = $item->price;
                            $item['discount'] = null;
                            $item['sum_total_cost'] = $totalCostSum;
        
                            return $item;
                        });
                    }
        
                    return $item;
                })->first();
        
                if (!$orders_collection) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid Order ID, Please check the Order ID!'], 404);
                }
        
                return view('products-package.products-list', [ 'orders_collection' => $orders_collection ])->render();
            }
        
            $data = [
                'title' => "Product Packing | " . CustomHelper::Get_website_name(),
                'today' => Carbon::today()->format('Y-m-d'),
                'orders_collection' => $orders_collection,
            ];
        
            return view('products-package.index', $data);
        
        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }
    }

    public function MarkProductPacked(Request $request)
    {
        try {
     
            if ( $request->order_vai == "Dukkan" ) {

                $order = DukaanOrderProduct::query()->where('order_id',$request->order_id)->where('order_vai',$request->order_vai)
                                        ->where('product_sku_id',$request->product_sku_id)->first();

                if (!$order) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid SKU ID, please check the SKU ID'], 404);
                }

                $order->update(['packed_status' => 1 ]);
            }

            if ( $request->order_vai == "woocommerce"  ) {

                $order = WoocommerceOrderProduct::query()->where('order_id',$request->order_id)->where('order_vai',$request->order_vai)
                                        ->where('sku',$request->product_sku_id)->first();

                if (!$order) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid SKU ID, please check the SKU ID'], 404);
                }

                $order->update(['packed_status' => 1 ]);
            }

            $orders_collection = Order::query()->where('order_id', $request->order_id)->get()->map(function ($item) {

                $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');

                if ($item->order_vai == "Dukkan") {
                    
                    $DukaanOrderProducts = DukaanOrderProduct::where('order_uuid', $item->order_uuid)->get();

                    $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');

                    $item['product_details'] = $DukaanOrderProducts->map(function ($item) use ($totalCostSum) {

                        $item['product_name'] = $item->product_slug;
                        $item['sku_id'] = $item->product_sku_id;
                        $item['product_total_cost'] = $item->line_item_total_cost;
                        $item['price'] = $item->selling_price;
                        $item['discount'] = $item->line_item_discount;
                        $item['sum_total_cost'] = $totalCostSum;
                        return $item;
                    });
                }

                if ($item->order_vai == "woocommerce") {

                    $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_uuid', $item->order_uuid)->get();

                    $totalCostSum = $WoocommerceOrderProduct->sum('total');

                    $item['product_details'] = $WoocommerceOrderProduct->map(function ($item) use ($totalCostSum) {

                        $item['product_name'] = $item->name;
                        $item['sku_id'] = $item->sku;
                        $item['product_total_cost'] = $item->total;
                        $item['price'] = $item->price;
                        $item['discount'] = null;
                        $item['sum_total_cost'] = $totalCostSum;

                        return $item;
                    });
                }

                return $item;
            })->first();

            return view('products-package.products-list', [ 'orders_collection' => $orders_collection ])->render();
           
        } catch (\Throwable $th) {

            return response()->json(['status' => 'error', 'message' => 'Invalid SKU ID, please check the SKU ID'], 404);
        }
    }
}