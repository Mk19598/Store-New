<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;
use App\Mail\OrderSendEmail;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use App\Helpers\CustomHelper;
use App\Models\WoocommerceOrder;
use App\Models\WoocommerceBuyer;
use App\Models\WoocommerceShipping;
use App\Models\WoocommerceOrderProduct;
use App\Models\InventoryManagement;
use App\Models\DukaanOrder;
use App\Models\DukaanBuyer;
use App\Models\DukaanOrderProduct;
use App\Models\Credentials;
use App\Models\ShippingLink;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\ContentTemplate;
use App\Models\EnvSetting;
use App\Models\OrderNotes;
use Mpdf\Mpdf;

class OrderController extends Controller
{
    // Storing the Order form - woocommerce & DUKAAN

    public function dukkan_orders_update( $days_limit){
        try {

            $Dukaan_API_TOKEN = Credentials::pluck('dukkan_api_token')->first();
            $Dukaan_store_Id =  CustomHelper::StoreId();
            $unique_id =  substr(uniqid(mt_rand(), true), 0, 11);

            $orders = []; 
            $hasNextPage = true;
            
            for ($page = 1; $hasNextPage ; $page++) {

                $response = Http::withHeaders(['Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,'Accept' => 'application/json',])
                                            ->get('https://api.mydukaan.io/api/seller-front/order-list/', [
                                                'ordering' => '-created_at',
                                                'utm_data' => 'True',
                                                'created_at_after' => Carbon::now()->subDays($days_limit)->toDateString(),
                                                'page' => $page,
                                            ]);

                if ($response->successful()) {

                    $orders = $response->json();
            
                    if (isset($orders['results'])) {

                        foreach ($orders['results'] as $key => $order) {

                            $order_respond = Http::withHeaders(['Authorization' => 'Bearer ' . $Dukaan_API_TOKEN, 'Accept' => 'application/json',])
                                                        ->get("https://api.mydukaan.io/api/order/seller/{$order['uuid']}/order/");
                            
            
                            $order_response =  $order_respond->json() ;
            
                            $existingOrder = Order::where('order_id', $order_response['data']['display_order_id'])->first();
            
                            if (!$existingOrder) {
            
                                Order::create([
                                    'order_vai' => 'Dukkan',
                                    'order_id'  => $order_response['data']['display_order_id'],
                                    'order_uuid'  => $order_response['data']['uuid'],
                                    'order_created_at' => $order_response['data']['created_at'], 
                                    'modified_at'  => $order_response['data']['modified_at'], 
                                    'uuid'         => $order_response['data']['uuid'], 
                                    'status'       => $order_response['data']['status'], 
                                    'payment_mode' => $order_response['data']['payment_mode'], 
            
                                    'total_cost'        => $order_response['data']['total_cost'], 
                                    'coupon_discount'   => $order_response['data']['coupon_discount'], 
                                    'delivery_cost'     => $order_response['data']['delivery_cost'], 
            
                                    'product_count'   => $order_response['data']['product_count'], 
            
                                    'currency_code'     =>  $order_response['data']['store_data']['currency']['cc'] , 
                                    'currency_symbol'   =>  $order_response['data']['store_data']['currency']['symbol'] , 
                                    'currency_name'     =>  $order_response['data']['store_data']['currency']['name'] , 
                                    
                                    'store_id'     =>  $order_response['data']['store_data']['id'] , 
                                    'store_name'   =>  $order_response['data']['store_data']['name'] , 
                                    'store_link'   =>  $order_response['data']['store_data']['link'] , 
                                    'store_image'  =>  $order_response['data']['store_data']['image'] , 
            
            
                                    'buyer_first_name'   => $order_response['data']['order_meta']['buyer_address']['buyer']['name'], 
                                    'buyer_last_name'    => null, 
                                    'buyer_email'        => $order_response['data']['order_meta']['buyer_address']['buyer']['email'], 
                                    'buyer_mobile_number'=> $order_response['data']['order_meta']['buyer_address']['buyer']['mobile'], 
            
                                    'buyer_line'   => $order_response['data']['order_meta']['buyer_address']['line'], 
                                    'buyer_area'   => $order_response['data']['order_meta']['buyer_address']['line_1'], 
                                    'buyer_city'   => $order_response['data']['order_meta']['buyer_address']['city'], 
                                    'buyer_state'  => $order_response['data']['order_meta']['buyer_address']['state'], 
                                    'buyer_county' => $order_response['data']['order_meta']['buyer_address']['country'], 
                                    'buyer_pin'    => $order_response['data']['order_meta']['buyer_address']['pin'], 
                                    'buyer_landmark' => $order_response['data']['order_meta']['buyer_address']['landmark'], 
                                    
                                    'buyer_shipping_first_name' => $order_response['data']['order_meta']['buyer_address']['buyer']['name'],
                                    'buyer_shipping_last_name'  => null,
                                    'buyer_shipping_address_1' => $order_response['data']['order_meta']['buyer_address']['line'], 
                                    'buyer_shipping_address_2' => $order_response['data']['order_meta']['buyer_address']['line_1'], 
                                    'buyer_shipping_city'   => $order_response['data']['order_meta']['buyer_address']['city'], 
                                    'buyer_shipping_state'  => $order_response['data']['order_meta']['buyer_address']['state'], 
                                    'buyer_shipping_county' => $order_response['data']['order_meta']['buyer_address']['country'], 
                                    'buyer_shipping_pin'    => $order_response['data']['order_meta']['buyer_address']['pin'], 
                                    'buyer_shipping_mobile_number' => $order_response['data']['order_meta']['buyer_address']['buyer']['mobile'],
                                    'unique_id' => $unique_id,
                                ]);
            
                                DukaanOrder::create([
                                    'order_id'          => $order['display_order_id'],
                                    'order_uuid'        => $order_response['data']['uuid'],
                                    'order_created_at'  => $order['created_at'],
                                    'created_at_utc'    => $order['created_at_utc'],
                                    'modified_at'       => $order['modified_at'],
                                    'modified_at_utc'   => $order['modified_at_utc'],
                                    'uuid'              => $order['uuid'],
                                    'status'            => $order['status'],
                                    'type'              => $order['type'],
                                    'return_status'     => $order['return_status'],
                                    'return_type'       => $order['return_type'],
                                    'payment_mode'      => $order['payment_mode'],
                                    'table_uuid'        => $order['table_uuid'],
                                    'reseller_commission' => $order['reseller_commission'],
                                    'is_new'              => $order['is_new'],
                                    'total_cost'          => $order['total_cost'],
                                    'channel'             => $order['channel'],
                                    'source'              => $order['source'],
                                    'image'               => $order['image'],
                                    'store_table_data'    => $order['store_table_data'],
                                    'product_count'       => $order['product_count'],
                                    'table_id'            => $order['table_id'],
                                    'seller_marked_prepaid' => $order['seller_marked_prepaid'],
                                    'sort_order_delivered'  => $order['sort_order_delivered'],
                                    'sort_order_pending'    => $order['sort_order_pending'],
                                    'utm_source'            => $order['utm_source'],
                                    'utm_medium'            => $order['utm_medium'],
                                    'utm_campaign'          => $order['utm_campaign'],
                                    'utm_term'              => $order['utm_term'],
                                    'utm_query'             => $order['utm_query'],
                                    'utm_content'           => $order['utm_content'],
                                    'unique_id'             => $unique_id,
                                ]);
                                
                                DukaanBuyer::create([
                                    'order_id'    => $order_response['data']['display_order_id'],
                                    'order_uuid'  => $order_response['data']['uuid'],
                                    'pin'       => $order_response['data']['order_meta']['buyer_address']['pin'],
                                    'area'      => $order_response['data']['order_meta']['buyer_address']['area'],
                                    'city'      => $order_response['data']['order_meta']['buyer_address']['city'],
                                    'line'      => $order_response['data']['order_meta']['buyer_address']['line'],
                                    'name'      => $order_response['data']['order_meta']['buyer_address']['buyer']['name'],
                                    'email'     => $order_response['data']['order_meta']['buyer_address']['buyer']['email'],
                                    'mobile'    => $order_response['data']['order_meta']['buyer_address']['buyer']['mobile'],
                                    'state'     => $order_response['data']['order_meta']['buyer_address']['state'],
                                    'county'    => $order_response['data']['order_meta']['buyer_address']['country'],
                                    'line_1'    => $order_response['data']['order_meta']['buyer_address']['line_1'],
                                    'region'    => $order_response['data']['order_meta']['buyer_address']['region'],
                                    'emirate'   => $order_response['data']['order_meta']['buyer_address']['emirate'],
                                    'landmark'  => $order_response['data']['order_meta']['buyer_address']['landmark'],
                                    'province'  => $order_response['data']['order_meta']['buyer_address']['province'],
                                    'prefecture'    => $order_response['data']['order_meta']['buyer_address']['prefecture'],
                                    'governorate'   => $order_response['data']['order_meta']['buyer_address']['governorate'],
                                    'unique_id'     => $unique_id,
                                ]);
            
            
                                foreach ($order_response['data']['products'] as $key => $orders_products) {
            
                                    $skuCode = collect(Http::withHeaders([
                                        'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                                        'Accept' => 'application/json',
                                    ])
                                    ->get("https://api.mydukaan.io/api/product/seller/{$Dukaan_store_Id}/product/{$orders_products['product_uuid']}/v2/")
                                    ->json()['data']['sku_data'] ?? [])->firstWhere('id', $orders_products['product_sku_id'])['sku_code'] ?? 'SKU not found';
            
                                    DukaanOrderProduct::create([ 
                                        'order_id'    => $order_response['data']['display_order_id'],
                                        'order_uuid'  => $order_response['data']['uuid'],
                                        'order_vai'   => 'Dukkan',
                                        "product_id"     =>  $orders_products['product_id'],
                                        "quantity"       => $orders_products['quantity'],
                                        "is_sku_edited"  => $orders_products['is_sku_edited'],
                                        "quantity_freed" => $orders_products['quantity_freed'],
                                        "product_slug"   => $orders_products['product_slug'],
                                        "line_item_id"   => $orders_products['line_item_id'],
                                        "line_item_state" => $orders_products['line_item_state'],
                                        "status"          => $orders_products['status'],
                                        "new_line_time"   => $orders_products['new_line_time'],
                                        "line_item_uuid"  => $orders_products['line_item_uuid'],
                                        "old_quantity"    => $orders_products['old_quantity'],
                                        "quantity_returned"   => $orders_products['quantity_returned'],
                                        "product_sku_id"      => $orders_products['product_sku_id'],
                                        "shipping_weight_kgs" => $orders_products['shipping_weight_kgs'],
                                        "default_staff_id"    => $orders_products['default_staff_id'],
                                        "default_staff_name"  => $orders_products['default_staff_name'],
                                        "shipment_id"         => $orders_products['line_item_tax'],
                                        "line_item_tax"       => $orders_products['line_item_discount'],
                                        "line_item_discount"  => $orders_products['line_item_discount'],
                                        "line_item_service_charges" => $orders_products['line_item_service_charges'],
                                        "line_item_delivery_cost"   => $orders_products['line_item_delivery_cost'],
                                        "selling_price"  => $orders_products['selling_price'],
                                        "original_price" => $orders_products['original_price'],
                                        "line_item_total_cost"    => $orders_products['line_item_total_cost'],
                                        "line_item_group"         => $orders_products['line_item_group'],
                                        "is_membership_line_item" => $orders_products['is_membership_line_item'],
                                        "gift_wrap_message"       => $orders_products['gift_wrap_message'],
                                        "name"  => $orders_products['name'],
                                        "image" => $orders_products['image'],
                                        "unit"  => $orders_products['unit'],
                                        "base_qty" => $orders_products['base_qty'],
                                        "product_uuid" => $orders_products['product_uuid'],
            
                                        "sku"          =>  $skuCode , 
                                        "barcode"      => InventoryManagement::where('sku',$skuCode)->pluck('barcode')->first(),
                                        'barcode_image' => InventoryManagement::where('sku',$skuCode)->pluck('barcode_image')->first(),
                                        "sku_weight_unit" =>  $orders_products['sku_weight_unit'] ?? null,
                                        "variant_size"    => $orders_products['variant_size'] ?? null,
            
                                        "gst_rate"        => $orders_products['gst_rate'],
                                        "item_gst_charge" => $orders_products['item_gst_charge'],
                                        "discount_per_unit" => $orders_products['discount_per_unit'],
                                        "return_enabled"    => $orders_products['return_enabled'],
                                        "replacement_enabled"   => $orders_products['replacement_enabled'],
                                        "return_duration_days"  => $orders_products['return_duration_days'],
                                        'order_created_at'      => $order_response['data']['created_at'], 
                                        'unique_id'     => $unique_id,
                                    ]);
                                }
                                
                                // DukaanShipping::create([]);
            
                                // Mail Sending
                                try {
                                    
                                    $orders_collection = Order::query()->where('order_id',$order_response['data']['display_order_id'],)->get()->map(function($item){
            
                                        $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');
                                        $item['order_vai'] = 'Dukkan';
                            
                                        $DukaanOrderProducts = DukaanOrderProduct::where('order_id', $item->order_id)->get();
                            
                                        $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');
                                        
                                        $item['product_details'] = $DukaanOrderProducts->map(function($item) use ($totalCostSum){
                            
                                            $item['product_name']   = $item->product_slug;
                                            $item['original_cost']  = $item->original_price;
                                            $item['price']      = $item->selling_price;
                                            $item['discount']   = $item->line_item_discount;
                                            $item['product_delivery_cost']  = $item->line_item_delivery_cost ;
                                            $item['product_total_cost']  = $item->line_item_total_cost ;
                                            $item['sum_total_cost'] = $totalCostSum; 
                                            return $item;
                                        });
                            
                                        return $item;
                                    })->first();
                            
                                    $data = [
                                        'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
                                        'Get_website_name' => CustomHelper::Get_website_name(),
                                        'orders_collection'  => $orders_collection,
                                    ];
                            
                                    // Mail::to(CustomHelper::Get_ADMIN_MAIL())->send(new OrderSendEmail($data));
            
                                } catch (\Illuminate\Mail\TransportException $e) {
                            
                                    Log::error('Mail Transport Error: ' . $e->getMessage());
                                
                                } catch (\Exception $e) {
                                
                                    Log::error('Failed to send email: ' . $e->getMessage());
                                
                                } catch (\Throwable $th) {
                                    
                                    Log::critical('Critical Error while sending mail: ' . $th->getMessage());
                                }
                            }
                        }
                    }
            
                    $hasNextPage = isset($orders['results']) && $orders['results'] !== null;
                            
                }
                else{
                    
                    $data = [ 'message' => $response->reason(), 'err_msg'  => $response->body(),];

                    return view('layouts.error-pages.500-Page',$data);
                }
            }

            $data = array(
                'title'    => "Orders Store-Update | ".CustomHelper::Get_website_name() ,
                'message'  => ("Orders have been updated till this current date & time"),
                'respond_message' => Str::title("The dukkan orders saved successfully"),
                'current_time'    => carbon::now()->format('l jS \of F Y h:i:s A'),
            );
            
            return view('orders.store-update',$data);

        } catch (\Throwable $th) {

            return view('layouts.error-pages.500-Page',['err_msg' => $th->getMessage()]);
        }
    }

    Public function woocommerce_orders_update($days_limit){

        try {
            
            $woocommerce_Credentials = Credentials::first();

            $woocommerce = new Client(
                $woocommerce_Credentials->woocommerce_url, 
                $woocommerce_Credentials->woocommerce_customer_key,       
                $woocommerce_Credentials->woocommerce_secret_key,    
                ['wp_api' => true,'version' => 'wc/v3' ]
            );

            $unique_id =  substr(uniqid(mt_rand(), true), 0, 11);
            $orders = [];

            for ($page = 1, $thirtyDaysAgo = Carbon::now()->subDays($days_limit)->toIso8601String(); ; $page++) {

                $orders = $woocommerce->get('orders', [
                    'after' => $thirtyDaysAgo,
                    'orderby' => 'date',
                    'order' => 'desc',
                    'per_page' => 50,
                    'page' => $page
                ]);

                foreach ($orders as $key => $order) {

                    $existingOrder = Order::where('order_id', $order->id)->first();
        
                    if (!$existingOrder) {
        
                        Order::create([
                            'order_vai' => 'woocommerce',
                            'order_id'  => $order->id,
                            'order_uuid' => $order->order_key, 
                            'order_created_at' => $order->date_created, 
                            'modified_at'      => $order->date_modified, 
                            'uuid'         => $order->order_key, 
                            'status'       => $order->status, 
                            'payment_mode' => $order->payment_method, 
        
                            'total_cost'   => $order->total, 
                            'delivery_cost'   => $order->shipping_total, 
                
                            'currency_code'     => $order->currency, 
                            'currency_symbol'   => $order->currency_symbol, 
                            'currency_name'     => null , 
        
                            'buyer_first_name' => $order->billing->first_name, 
                            'buyer_last_name'  => $order->billing->last_name, 
                            'buyer_email'      => $order->billing->email, 
                            'buyer_mobile_number' => $order->billing->phone, 
        
                            'buyer_line' => $order->billing->address_1, 
                            'buyer_area' => $order->billing->address_2, 
                            'buyer_city' => $order->billing->city, 
                            'buyer_state' => $order->billing->state, 
                            'buyer_county' => $order->billing->country, 
                            'buyer_pin'    => $order->billing->postcode, 
                            
                            'buyer_shipping_first_name' => $order->shipping->first_name,
                            'buyer_shipping_last_name'  => $order->shipping->last_name,
                            'buyer_shipping_address_1' => $order->shipping->address_1, 
                            'buyer_shipping_address_2' => $order->shipping->address_2, 
                            'buyer_shipping_city' => $order->shipping->city, 
                            'buyer_shipping_state' => $order->shipping->state, 
                            'buyer_shipping_county' => $order->shipping->country, 
                            'buyer_shipping_pin' => $order->shipping->postcode, 
                            'buyer_shipping_mobile_number' => $order->shipping->phone, 
                            'unique_id' => $unique_id,
                        ]);
        
                        WoocommerceOrder::create([
                            'order_id' => $order->id ,
                            'order_uuid' => $order->order_key, 
                            'status'   => $order->status,
                            'currency' => $order->currency,
                            'version'  => $order->version ,
                            'prices_include_tax'=> $order->prices_include_tax ,
                            'date_created'      => $order->date_created ,
                            'date_modified'     => $order->date_modified ,
                            'discount_total'    => $order->discount_total ,
                            'discount_tax'      => $order->discount_tax ,
                            'shipping_total'    => $order->shipping_total ,
                            'shipping_tax'      => $order->shipping_tax ,
                            'cart_tax'          => $order->cart_tax ,
                            'total'             => $order->total,
                            'total_tax'         => $order->total_tax ,
                            'customer_id'       => $order->customer_id ,
                            'payment_method'    => $order->payment_method ,
                            'payment_method_title'  => $order->payment_method_title ,
                            'transaction_id'        => $order->transaction_id ,
                            'customer_ip_address'   => $order->customer_ip_address ,
                            'customer_user_agent'   => $order->customer_user_agent ,
                            'created_via'           => $order->created_via ,
                            'customer_note'         => $order->customer_note ,
                            'date_completed'        => $order->date_completed ,
                            'date_paid'             => $order->date_paid ,
                            'cart_hash'             => $order->cart_hash ,
                            'number'                => $order->number ,
                            'payment_url'           => $order->payment_url ,
                            'is_editable'           => $order->is_editable ,
                            'needs_payment'         => $order->needs_payment ,
                            'needs_processing'      => $order->needs_processing ,
                            'date_created_gmt'      => $order->date_created_gmt ,
                            'date_modified_gmt'     => $order->date_modified_gmt ,
                            'date_completed_gmt'    => $order->date_completed_gmt ,
                            'date_paid_gmt'         => $order->date_paid_gmt ,
                            'currency_symbol'       => $order->currency_symbol,
                            'unique_id'             => $unique_id,
                        ]);
        
                        WoocommerceBuyer::create([
                            'order_id' => $order->id ,
                            'order_uuid' => $order->order_key, 
                            'first_name' => $order->billing->first_name,
                            'last_name'  => $order->billing->last_name,
                            'company'    => $order->billing->company,
                            'address_1'  => $order->billing->address_1,
                            'address_2'  => $order->billing->address_2,
                            'city'       => $order->billing->city,
                            'state'      => $order->billing->state,
                            'postcode'   => $order->billing->postcode,
                            'country'    => $order->billing->country,
                            'email'      => $order->billing->email,
                            'phone'      => $order->billing->phone,
                            'unique_id' => $unique_id,
                        ]);
        
                        WoocommerceShipping::create([
                            'order_id'   => $order->id ,
                            'order_uuid' => $order->order_key, 
                            'first_name' => $order->shipping->first_name,
                            'last_name'  => $order->shipping->last_name,
                            'company'    => $order->shipping->company,
                            'address_1' => $order->shipping->address_1,
                            'address_2' => $order->shipping->address_2,
                            'city'      => $order->shipping->city,
                            'state'     => $order->shipping->state,
                            'postcode'  => $order->shipping->postcode,
                            'country'   => $order->shipping->country,
                            'unique_id' => $unique_id,
                        ]);
        
                        foreach($order->line_items as $key => $order_product){
        
                            WoocommerceOrderProduct::create([
                                'order_id' => $order->id ,
                                'order_vai' => 'woocommerce',
                                'order_uuid' => $order->order_key, 
                                'name'       => $order_product->name,
                                'product_id' => $order_product->product_id,
                                'variation_id' => $order_product->variation_id,
                                'quantity'     => $order_product->quantity,
                                'tax_class'    => $order_product->tax_class,
                                'subtotal'     => $order_product->subtotal,
                                'subtotal_tax' => $order_product->subtotal_tax,
                                'total'         => $order_product->total,
                                'total_tax'     => $order_product->total_tax,
                                // 'taxes'         => !empty($order_product->taxes),
                                'sku'           => $order_product->sku,
                                'price'         => $order_product->price,
                                'barcode'       => InventoryManagement::where('sku',$order_product->sku)->pluck('barcode')->first(),
                                'barcode_image' => InventoryManagement::where('sku',$order_product->sku)->pluck('barcode_image')->first(),
                                'image'         => !empty($order_product->image) ? $order_product->image->src : null,
                                'parent_name'   => $order_product->parent_name,
                                'order_created_at'  => $order->date_created, 
                                'unique_id' => $unique_id,
                            ]);
                        }
        
                        // Mail Sending 
                        try {
        
                            $orders_collection = Order::query()->where('order_id',$order->id)->get()->map(function($item){
                
                                $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y');
                    
                                $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_id', $item->order_id )->get();
                                $item['order_vai'] = 'woocommerce';
                    
                                $totalCostSum = $WoocommerceOrderProduct->sum('total');
                    
                                $item['product_details'] = $WoocommerceOrderProduct->map(function($item) use($totalCostSum) {
                                    $item['product_name'] = $item->name;
                                    $item['product_total_cost'] = $item->total;
                                    $item['price']     = $item->price;
                                    $item['discount']  = null ;
                                    $item['product_delivery_cost']  = null ;
                                    $item['sum_total_cost'] = $totalCostSum; 
                    
                                    return $item;
                                });
                    
                                return $item;
                            })->first();
        
                            $data = [
                                'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
                                'Get_website_name' => CustomHelper::Get_website_name(),
                                'orders_collection'  => $orders_collection,
                            ];
                            
                            // Mail::to(CustomHelper::Get_ADMIN_MAIL())->send(new OrderSendEmail($data));
        
                        } catch (\Illuminate\Mail\TransportException $e) {
                        
                            Log::error('Mail Transport Error: ' . $e->getMessage());
                        
                        } catch (\Exception $e) {
                        
                            Log::error('Failed to send email: ' . $e->getMessage());
                        
                        } catch (\Throwable $th) {
                            
                            Log::critical('Critical Error while sending mail: ' . $th->getMessage());
                        }
                    }
                }

                if (count($orders) < 50) break;
            }

            $data = array(
                'title'    => "Orders Store-Update | ".CustomHelper::Get_website_name() ,
                'message'  => ("Orders have been updated till this current date & time"),
                'respond_message' => Str::title("The woocommerce orders saved successfully"),
                'current_time'    => carbon::now()->format('l jS \of F Y h:i:s A'),
            );
            
            return view('orders.store-update',$data);

        } catch (\Throwable $th) {

            return view('layouts.error-pages.500-Page',['err_msg' => $th->getMessage()]);
        }
    }

    // Order Lists
    public function index(Request $request)
    {
        try {

            $orders = Order::query();

                    // Apply filters

            if($request->ajax()){

                if ($request->filled('date_from') && $request->filled('time_from')) {
                    $orders->where('order_created_at', '>=', "{$request->date_from}T{$request->time_from}:00");
                }
                
                if ($request->filled('date_to') && $request->filled('time_to')) {
                    $orders->where('order_created_at', '<=', "{$request->date_to}T{$request->time_to}:00");
                }
                
                if ($request->filled('status') && $request->status !== 'all') {

                    $statusMap = [
                        'pending'    => ['pending', '0'],
                        'completed'  => ['completed', 5],
                        'cancelled'  => ['cancelled','failed', 4, 7 , 6],
                        'refunded'   => ['refunded', 10],
                        'processing' => ['processing' ],
                        'shipped'    => [ 'order-shipped', 3],
                        'Packed'     => [ 'Packed'],
                    ];
                
                    if (isset($statusMap[$request->status])) {
                        $orders->whereIn('status', $statusMap[$request->status]);
                    }else{
                        $orders->where('status', $request->status);
                    }
                }
            }

            $orders = $orders->when($request->filled('date_from') || $request->filled('date_to') || ($request->filled('status') && $request->status !== 'all'), 
                                
                                function ($query){

                                    return $query->orderBy('order_created_at', 'asc');

                                    })->with('trackingLinks')->get()->map(function($item) {

                                        $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y H:i:s');

                                        $statusColors = [

                                            'woocommerce' => [
                                                'pending'    => 'pending',
                                                'processing' => 'processing',
                                                'on-hold'    => 'processing',
                                                'completed'  => 'completed',
                                                'cancelled'  => 'cancelled',
                                                'refunded'   => 'refunded',
                                                'failed'     => 'cancelled',
                                                'order-shipped' => 'shipped',
                                                'Packed'     => 'Packed',
                                                'default'    => 'cancelled'
                                            ],

                                            'Dukkan' => [
                                                '-1' => ['label' => 'ABANDONED / DRAFT', 'color' => 'cancelled'],
                                                '0'  => ['label' => 'PENDING', 'color' => 'pending'],
                                                '1'  => ['label' => 'ACCEPTED', 'color' => 'completed'],
                                                '2'  => ['label' => 'REJECTED', 'color' => 'cancelled'],
                                                '3'  => ['label' => 'SHIPPED', 'color' => 'shipped'],
                                                '4'  => ['label' => 'CANCELLED', 'color' => 'cancelled'],
                                                '5'  => ['label' => 'DELIVERED', 'color' => 'completed'],
                                                '6'  => ['label' => 'FAILED', 'color' => 'cancelled'],
                                                '7'  => ['label' => 'CANCELLED BY CUSTOMER', 'color' => 'cancelled'],
                                                '10' => ['label' => 'RETURNED', 'color' => 'refunded'],
                                                'Packed' => ['label' => 'Packed', 'color' => 'Packed'],
                                                'default' => ['label' => 'UNKNOWN', 'color' => 'default-color']
                                            ]
                                        ];

                                        if ($item->order_vai == "woocommerce") {
                                            $item['status_color'] =  $statusColors['woocommerce'][$item->status] ?? "default-color";
                                            $item['status']       = ucfirst(strtolower($item->status)) ;
                                        }

                                        if ($item->order_vai == "Dukkan") {
                                            $dukkanStatus = $statusColors['Dukkan'][$item->status] ?? $statusColors['Dukkan']['default'];
                                            $item['status'] = ucfirst(strtolower($dukkanStatus['label']));
                                            $item['status_color'] = $dukkanStatus['color'];
                                        }

                                        $item['tracking_links'] = $item->trackingLinks->pluck('tracking_link');

                                        return $item;
                                });

            $data = array( 'orders' => $orders, 
                            'title'  => "Orders | ".CustomHelper::Get_website_name()  ,
                            'order_count' => $orders->count(),
                            'woocommerce_order_count' => $orders->where('order_vai','woocommerce')->count(),
                            'Dukkan_order_count' => $orders->where('order_vai','Dukkan')->count(),
                            'today' => Carbon::today()->format('Y-m-d') ,
                        );

                // filter
            if($request->ajax()){

                return view('orders.index-table', $data)->render();
            }

            return view('orders.index', $data);
            
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return view('layouts.error-pages.404-Page');
        }
    }

    // Order Invoice PDF
    public function orders_invoice_pdf(Request $request, $order_uuid)
    {
        try {

            $order_uuid = !is_null($request->input('selectedOrders')) ? $request->input('selectedOrders') : [$order_uuid];
    
            $orders_collection = Order::query()->whereIn('order_uuid',$order_uuid)->get()->map(function($item){

                $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y h:i:s A');
    
                if ($item->order_vai == "Dukkan" ) {
    
                    $DukaanOrderProducts = DukaanOrderProduct::where('order_uuid', $item->order_uuid)->get();
    
                    $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');
                    
                    $item['product_details'] = $DukaanOrderProducts->map(function($item) use ($totalCostSum){

                        $item['product_name']   = $item->name;
                        $item['original_cost']  = $item->original_price;
                        $item['price']      = $item->selling_price;
                        $item['discount']   = $item->line_item_discount;
                        $item['product_delivery_cost']  = $item->line_item_delivery_cost ;
                        $item['product_total_cost']  = $item->line_item_total_cost ;
                        $item['sum_total_cost'] = $totalCostSum; 
                        return $item;
                    });
                }
    
                if ($item->order_vai == "woocommerce") {
    
                    $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_uuid', $item->order_uuid)->get();
    
                    $totalCostSum = $WoocommerceOrderProduct->sum('total');
    
                    $item['product_details'] = $WoocommerceOrderProduct->map(function($item) use($totalCostSum) {
                        $item['product_name'] = $item->name;
                        $item['product_total_cost'] = $item->total;
                        $item['price']     = $item->price;
                        $item['discount']  = null ;
                        $item['product_delivery_cost']  = null ;
                        $item['sum_total_cost'] = $totalCostSum; 
    
                        return $item;
                    });
                }
    
                return $item;
            });
    
            $data = array(
                'orders_collection' => $orders_collection,
                'Get_website_name'  => CustomHelper::Get_website_name(),
                'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
                'title'  => "Invoice | ".CustomHelper::Get_website_name(),
            );
    

            // MPDF
            $mpdf = new \Mpdf\Mpdf( CustomHelper::Mpdf_font_setup());
            
            $data = array(
                'orders_collection' => $orders_collection,
                'Get_website_name'  => CustomHelper::Get_website_name(),
                'Get_website_logo_url'  => CustomHelper::Get_website_logo_url(),
                'title'  => "Invoice | ".CustomHelper::Get_website_name(),
            );
            
            $html = view('orders.PDF.invoice', $data)->render();

            $mpdf->WriteHTML($html);

            $pdfName =  ($orders_collection[0]->order_id ?? Str::random(8)) .'-Invoice' . '.pdf';

            return response($mpdf->Output($pdfName, 'I'))->header('Content-Type', 'application/pdf');

        } catch (\Throwable $th) {
            // return $th->getMessage();
            return view('layouts.error-pages.404-Page');
        }
    }

    public function tracking_links(Request $request)
    {
        $data = $request->all();
        
        $orders = Order::query()->where('order_uuid', $data['order_id'])->first();

        $trackingLinks = $data['tracking_links'] ?? [];

        $courierId = $data['courier_id'];
        
        $existingLinks = ShippingLink::where('order_id', $data['order_id'])->pluck('tracking_link')->toArray();

        $newLinks = array_diff($trackingLinks, $existingLinks);

        foreach ($newLinks as $newLink) {

            ShippingLink::create([
                'order_id' => $data['order_id'],
                'tracking_link' => $newLink,
                'courier_id' => $courierId
            ]);
        
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://shipway.in/api/PushOrderData', [
                    'username' => CustomHelper::Shipping_Username(),
                    'password' => CustomHelper::Shipping_Password(),
                    'carrier_id' => $courierId,
                    'awb' => $newLink,
                    'order_id' => $orders->order_id,
                    'first_name' => $orders->buyer_first_name,
                    'last_name' => $orders->buyer_last_name,
                    'email' => $orders->buyer_email,
                    'phone' => $orders->buyer_mobile_number,
                    'products' => 'N/A',
                    'company' => CustomHelper::Get_website_name(),
                    'shipment_type' => '1',
            ]);
            // print($response->successful());exit;
            // print($response->status());exit;
            // print($response->body());exit;
            // if ($response->successful()) {
            //     $data = $response->json();
            //     return $data;
            // } else {
            //     $data['status'] = $response->status();
            //     $data['body'] = $response->body();
            //     return $data;
            // }

            // Removed Because of Web in use 

            // $template = ContentTemplate::where('id','=',4)->first(); 
            // $template_description = $template->template_content ;
            // $EnvSetting = EnvSetting::first(); 
            // $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
            // $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;
            
            // $template_change = [
            //     "{Name}",
            //     "{orderno}",
            // ];
        
            // $template_content = [
            //     $orders->buyer_first_name.' '.$orders->buyer_last_name,      
            //     $orders->order_id, 
            // ];
        
            // $personalized_message = str_replace($template_change, $template_content, $template_description);
                             
            // $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
            //     'number' => '91' . $orders->buyer_mobile_number,
            //     'type' => 'text',
            //     'message' => html_entity_decode($personalized_message),
            //     'instance_id' => $INSTANCE_ID,
            //     'access_token' => $accessToken,
            // ]);

        }

        // return $response;
        
        return response()->json(['success' => true, 'message' => 'Notes added successfully.']);
        
    }

    public function getTrackingLinks($orderId)
    {
        $order = Order::where('order_uuid', $orderId)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $trackingLinks = $order->trackingLinks;

        $courierId = ShippingLink::where('order_id', $orderId)->value('courier_id');

        return response()->json([
            'tracking_links' => $trackingLinks->pluck('tracking_link')->toArray(),
            'courier_id' => $courierId,
        ]);
    }

    public function shipping_label(Request $request)
    {
        $orderId = $request->input('selectedOrders');

        $orders = Order::whereIn('id', $orderId)->get();

        $orders = Order::query()->whereIn('id', $orderId)->get()->map(function($item){

            $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y H:i:s');

            $generator = new BarcodeGeneratorPNG();
            $item['barcode'] = base64_encode($generator->getBarcode($item->order_id, $generator::TYPE_CODE_128));

            if ($item->order_vai == "Dukkan" ) {

                $DukaanOrderProducts = DukaanOrderProduct::where('order_uuid', $item->order_uuid)->get();

                $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');
                
                $item['product_details'] = $DukaanOrderProducts->map(function($item) use ($totalCostSum){
                    $item['product_name'] = $item->name;
                    $item['total_cost']  = $item->line_item_total_cost;
                    $item['price']      = $item->selling_price;
                    $item['sum_total_cost'] = $totalCostSum; 
                    return $item;
                });
            }

            if ($item->order_vai == "woocommerce") {

                $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_uuid', $item->order_uuid)->get();

                $totalCostSum = $WoocommerceOrderProduct->sum('total');

                $item['product_details'] = $WoocommerceOrderProduct->map(function($item) use($totalCostSum) {
                    $item['product_name'] = $item->name;
                    $item['total_cost'] = $item->total;
                    $item['price']     = $item->price;
                    $item['sum_total_cost'] = $totalCostSum; 

                    return $item;
                });
            }

            return $item;
        });

        $data = array(
            'orders' => $orders
        );
        
        $pdf = Pdf::loadView('orders.label', $data)->setPaper('a4', 'portrait')->setOption('margin-top', 0)
        ->setOption('margin-bottom', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);    
        
        return $pdf->download('shipping_labels.pdf');
        
    }

    public function addOrderNotes(Request $request)
    {
        $data = $request->all();
        
        $orders = Order::query()->where('order_uuid', $data['order_id'])->first();
        $order = Order::where('order_uuid', $data['order_id'])->first();

        $notesLinks = $data['notes'] ?? [];
        
        $existingnotes = OrderNotes::where('order_id', $data['order_id'])->pluck('notes')->toArray();

        $newLinks = array_diff($notesLinks, $existingnotes);

        foreach ($newLinks as $newLink) {
            OrderNotes::create([
                'order_id' => $data['order_id'],
                'notes' => $newLink,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Notes added successfully.']);
    }

    public function getOrderNotes($orderId)
    {
        $notes = OrderNotes::where('order_id', $orderId)->get(); 

        if ($notes) {
            return response()->json(['notes' => $notes], 200);
        }
        return response()->json(['notes' => []], 200);
    }   
    
    public function shipping_label_pdf(Request $request, $order_uuid)
    {
        try {
            $orders = Order::where('order_uuid', $order_uuid)->get()->map(function($item){

                $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y h:i:s A');
    
                $generator = new BarcodeGeneratorPNG();
                
                $item['barcode'] = base64_encode($generator->getBarcode($item->order_id, $generator::TYPE_CODE_128));
    
                if ($item->order_vai == "Dukkan" ) {
    
                    $DukaanOrderProducts = DukaanOrderProduct::where('order_uuid', $item->order_uuid)->get();
    
                    $totalCostSum = $DukaanOrderProducts->sum('line_item_total_cost');
                    
                    $item['product_details'] = $DukaanOrderProducts->map(function($item) use ($totalCostSum){
                        $item['product_name'] = $item->name;
                        $item['total_cost']  = $item->line_item_total_cost;
                        $item['price']      = $item->selling_price;
                        $item['sum_total_cost'] = $totalCostSum; 
                        return $item;
                    });
                }
    
                if ($item->order_vai == "woocommerce") {
    
                    $WoocommerceOrderProduct = WoocommerceOrderProduct::where('order_uuid', $item->order_uuid)->get();
    
                    $totalCostSum = $WoocommerceOrderProduct->sum('total');
    
                    $item['product_details'] = $WoocommerceOrderProduct->map(function($item) use($totalCostSum) {
                        $item['product_name'] = $item->name;
                        $item['total_cost']   = $item->total;
                        $item['price']        = $item->price;
                        $item['sum_total_cost'] = $totalCostSum; 
                        return $item;
                    });
                }
    
                return $item;
            });
    
            $data = array(
                        'orders' => $orders,
                        'title' => "Shipping Label | ".CustomHelper::Get_website_name() ,
                        'current_time' => carbon::now()->format('l jS \of F Y h:i:s A'),
                    );
                        
            // MPDF
    
            $setup = CustomHelper::Mpdf_font_setup();
            $setup += ['mode' => 'utf-8', 'format' => 'A4', 'orientation' => 'L'];
    
            $mpdf = new \Mpdf\Mpdf( $setup );
    
            $html = view('orders.label', $data)->render();
    
            $mpdf->WriteHTML($html);

            $ShippingLabel =  ($orders[0]->order_id ?? Str::random(8)). '-Shipping-Label'. '.pdf';

            return response($mpdf->Output($ShippingLabel, 'I'))->header('Content-Type', 'application/pdf');

        } catch (\Throwable $th) {
            return view('layouts.error-pages.404-Page');
        }
    }
}