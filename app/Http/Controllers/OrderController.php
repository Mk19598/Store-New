<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Models\WoocommerceOrder;
use App\Models\WoocommerceBuyer;
use App\Models\WoocommerceShipping;
use App\Models\WoocommerceProduct;
use App\Models\DukaanOrder;
use App\Models\DukaanBuyer;

class OrderController extends Controller
{

    // Storing the Order form - woocommerce & DUKAAN

    public function orders_store()
    {
        try {
            
            $WooCommerce = $this->WooCommerce();
            $Dukaan =  $this->Dukaan();

            if( $WooCommerce['status'] == false ){
                return response()->json( $WooCommerce, $WooCommerce['status_code']);
            }
            
            if( $Dukaan['status'] == false ){
                return response()->json( $Dukaan, $Dukaan['status_code']);
            }

            return response()->json(array(
                'status' => true,
                'message' => ucwords("{$WooCommerce['message']} & {$Dukaan['message']}"),
                'woocommerce_data' => $WooCommerce,
                'DUKAAN_data' => $Dukaan,
            ), 200);
            
        } catch (\Throwable $th) {

            return response()->json(array(
                "status" => false,
                "message" => $th->getMessage() ,
            ), 400);
        }
    }

    private function WooCommerce(){

        $WooCommerce_url = env('woocommerce_url') ; 
        $WooCommerce_customer_key = env('woocommerce_customer_key') ; 
        $WooCommerce_consumer_secret = env('woocommerce_consumer_secret') ; 

        $url = $WooCommerce_url . '/wp-json/wc/v3/orders';

        $response = Http::withBasicAuth($WooCommerce_customer_key, $WooCommerce_consumer_secret)->get($url);

        if ($response->successful()) {

            $orders =  $response->json() ;

            $unique_id =  substr(uniqid(mt_rand(), true), 0, 11);
            
            foreach ($orders as $key => $order) {

                $existingOrder = Order::where('order_id', $order['id'])->first();

                if (!$existingOrder) {

                    Order::create([
                        'order_vai' => 'woocommerce',
                        'order_id'  => $order['id'],
                        'order_created_at' => $order['date_created'], 
                        'modified_at'      => $order['date_modified'], 
                        'uuid'         => $order['order_key'], 
                        'status'       => $order['status'], 
                        'payment_mode' => $order['payment_method'], 
                        'total_cost'   => $order['total'], 
                        'currency'     => $order['currency'], 

                        'buyer_first_name' => $order['billing']['first_name'], 
                        'buyer_last_name'  => $order['billing']['last_name'], 
                        'buyer_email'      => $order['billing']['email'], 
                        'buyer_mobile_number' => $order['billing']['phone'], 

                        'buyer_line' => $order['billing']['address_1'], 
                        'buyer_area' => $order['billing']['address_2'], 
                        'buyer_city' => $order['billing']['city'], 
                        'buyer_state' => $order['billing']['state'], 
                        'buyer_county' => $order['billing']['country'], 
                        'buyer_pin'    => $order['billing']['postcode'], 
                        
                        'buyer_shipping_address_1' => $order['shipping']['address_1'], 
                        'buyer_shipping_address_2' => $order['shipping']['address_2'], 
                        'buyer_shipping_city' => $order['shipping']['city'], 
                        'buyer_shipping_state' => $order['shipping']['state'], 
                        'buyer_shipping_county' => $order['shipping']['country'], 
                        'buyer_shipping_pin' => $order['shipping']['postcode'], 
                        'buyer_shipping_mobile_number' => $order['shipping']['phone'], 
                        'unique_id' => $unique_id,
                    ]);

                    WoocommerceOrder::create([
                        'order_id' => $order['id'] ,
                        'status'   => $order['status'],
                        'currency' => $order['currency'],
                        'version'  => $order['version'] ,
                        'prices_include_tax'=> $order['prices_include_tax'] ,
                        'date_created'      => $order['date_created'] ,
                        'date_modified'     => $order['date_modified'] ,
                        'discount_total'    => $order['discount_total'] ,
                        'discount_tax'      => $order['discount_tax'] ,
                        'shipping_total'    => $order['shipping_total'] ,
                        'shipping_tax'      => $order['shipping_tax'] ,
                        'cart_tax'          => $order['cart_tax'] ,
                        'total'             => $order['total'],
                        'total_tax'         => $order['total_tax'] ,
                        'customer_id'       => $order['customer_id'] ,
                        'payment_method'    => $order['payment_method'] ,
                        'payment_method_title'  => $order['payment_method_title'] ,
                        'transaction_id'        => $order['transaction_id'] ,
                        'customer_ip_address'   => $order['customer_ip_address'] ,
                        'customer_user_agent'   => $order['customer_user_agent'] ,
                        'created_via'           => $order['created_via'] ,
                        'customer_note'         => $order['customer_note'] ,
                        'date_completed'        => $order['date_completed'] ,
                        'date_paid'             => $order['date_paid'] ,
                        'cart_hash'             => $order['cart_hash'] ,
                        'number'                => $order['number'] ,
                        'payment_url'           => $order['payment_url'] ,
                        'is_editable'           => $order['is_editable'] ,
                        'needs_payment'         => $order['needs_payment'] ,
                        'needs_processing'      => $order['needs_processing'] ,
                        'date_created_gmt'      => $order['date_created_gmt'] ,
                        'date_modified_gmt'     => $order['date_modified_gmt'] ,
                        'date_completed_gmt'    => $order['date_completed_gmt'] ,
                        'date_paid_gmt'         => $order['date_paid_gmt'] ,
                        'currency_symbol'       => $order['currency_symbol'],
                        // 'unique_id '=> $order['unique_id'],
                    ]);

                    WoocommerceBuyer::create([
                        'order_id' => $order['id'] ,
                        'first_name' => $order['billing']['first_name'],
                        'last_name'  => $order['billing']['last_name'],
                        'company'    => $order['billing']['company'],
                        'address_1'  => $order['billing']['address_1'],
                        'address_2'  => $order['billing']['address_2'],
                        'city'       => $order['billing']['city'],
                        'state'      => $order['billing']['state'],
                        'postcode'   => $order['billing']['postcode'],
                        'country'    => $order['billing']['country'],
                        'email'      => $order['billing']['email'],
                        'phone'      => $order['billing']['phone'],
                        'unique_id' => $unique_id,
                    ]);

                    WoocommerceShipping::create([
                        'order_id' => $order['id'] ,
                        'first_name' => $order['shipping']['first_name'],
                        'last_name'  => $order['shipping']['last_name'],
                        'company'    => $order['shipping']['company'],
                        'address_1' => $order['shipping']['address_1'],
                        'address_2' => $order['shipping']['address_2'],
                        'city'      => $order['shipping']['city'],
                        'state'     => $order['shipping']['state'],
                        'postcode'  => $order['shipping']['postcode'],
                        'country'   => $order['shipping']['country'],
                        'unique_id' => $unique_id,
                    ]);

                    foreach($order['line_items'] as $key => $order_product){

                        WoocommerceProduct::create([
                            'order_id' => $order_product['id'] ,
                            'name'       => $order_product['name'],
                            'product_id' => $order_product['product_id'],
                            'variation_id' => $order_product['variation_id'],
                            'quantity'     => $order_product['quantity'],
                            'tax_class'    => $order_product['tax_class'],
                            'subtotal'     => $order_product['subtotal'],
                            'subtotal_tax' => $order_product['subtotal_tax'],
                            'total'         => $order_product['total'],
                            'total_tax'     => $order_product['total_tax'],
                            // 'taxes'         => !empty($order_product['taxes']),
                            'sku'           => $order_product['sku'],
                            'price'         => $order_product['price'],
                            'image'         => !empty($order_product['image']) ? $order_product['image']['src'] : null,
                            'parent_name'   => $order_product['parent_name'],
                            'unique_id' => $unique_id,
                        ]);
                    }

                    $result = array(
                        'status'      => true,
                        'status_code' => 200,
                        'message'     => Str::title("The woocommerce orders saved successfully"),
                        'body'        => $response->reason(),
                    );

                }else{

                     // Log::info("Skipping duplicate order with ID: " . $order['display_order_id']);

                     $result = array(
                        'status'      => true,
                        'status_code' => 200,
                        'message'     => Str::title("Skipping woocommerce duplicate order with ID"),
                        'body'        => $response->reason(),
                    );
                }
            }

        }else{

            $result = array(
                'status'      => false,
                'status_code' => $response->status(),
                'message'     => $response->reason(),
                'body'        => $response->body(),
            );
        }

        return $result;
    }

    private function Dukaan(){
        
        $Dukaan_API_TOKEN = env('DUKAAN_API_TOKEN'); 

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $Dukaan_API_TOKEN, 'Accept' => 'application/json',])
                        ->get('https://api.mydukaan.io/api/seller-front/order-list/', [
                            'ordering' => '-created_at',
                            'utm_data' => 'True',
                        ]);

        if ($response->successful()) {

            $orders =  $response->json() ;

            $unique_id =  substr(uniqid(mt_rand(), true), 0, 11);

            foreach ($orders['results'] as $key => $order) {

                $existingOrder = Order::where('order_id', $order['display_order_id'])->first();

                if (!$existingOrder) {

                    Order::create([
                        'order_vai' => 'Dukkan',
                        'order_id'  => $order['display_order_id'],
                        'order_created_at' => $order['created_at'], 
                        'modified_at'  => $order['modified_at'], 
                        'uuid'         => $order['uuid'], 
                        'status'       => $order['status'], 
                        'payment_mode' => $order['payment_mode'], 
                        'total_cost'   => $order['total_cost'], 
                        'currency'     => null, 

                        'buyer_first_name'   => $order['buyer_address']['buyer']['name'], 
                        'buyer_last_name'    => null, 
                        'buyer_email'        => $order['buyer_address']['buyer']['email'], 
                        'buyer_mobile_number'=> $order['buyer_address']['buyer']['mobile'], 

                        'buyer_line'   => $order['buyer_address']['line'], 
                        'buyer_area'   => $order['buyer_address']['line_1'], 
                        'buyer_city'   => $order['buyer_address']['city'], 
                        'buyer_state'  => $order['buyer_address']['state'], 
                        'buyer_county' => $order['buyer_address']['country'], 
                        'buyer_pin'    => $order['buyer_address']['pin'], 
                        
                        'buyer_shipping_address_1' => null, 
                        'buyer_shipping_address_2' => null, 
                        'buyer_shipping_city'   => null, 
                        'buyer_shipping_state'  => null, 
                        'buyer_shipping_county' => null, 
                        'buyer_shipping_pin'    => null, 
                        'buyer_shipping_mobile_number' => null, 
                        'unique_id' => $unique_id,
                    ]);

                    DukaanOrder::create([
                        'order_id'          => $order['display_order_id'],
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
                        'order_id'  => $order['buyer_address']['line'],
                        'pin'       => $order['buyer_address']['pin'],
                        'area'      => $order['buyer_address']['area'],
                        'city'      => $order['buyer_address']['city'],
                        'line'      => $order['buyer_address']['line'],
                        'name'      => $order['buyer_address']['buyer']['name'],
                        'email'     => $order['buyer_address']['buyer']['email'],
                        'mobile'    => $order['buyer_address']['buyer']['mobile'],
                        'state'     => $order['buyer_address']['state'],
                        'county'    => $order['buyer_address']['country'],
                        'line_1'    => $order['buyer_address']['line_1'],
                        'region'    => $order['buyer_address']['region'],
                        'emirate'   => $order['buyer_address']['emirate'],
                        'landmark'  => $order['buyer_address']['landmark'],
                        'province'  => $order['buyer_address']['province'],
                        'prefecture'    => $order['buyer_address']['prefecture'],
                        'governorate'   => $order['buyer_address']['governorate'],
                        'unique_id'     => $unique_id,
                    ]);
                    
                    // DukaanProduct::create([ ]);
                    
                    // DukaanShipping::create([]);

                    $result = array(
                        'status'      => true,
                        'status_code' => 200,
                        'message'     => Str::title("The Dukaan orders saved successfully"),
                        'body'        => $response->reason(),
                    );
                }else{

                    // Log::info("Skipping duplicate order with ID: " . $order['display_order_id']);

                    $result = array(
                        'status'      => true,
                        'status_code' => 200,
                        'message'     => Str::title("Skipping dukkan duplicate order with ID"),
                        'body'        => $response->reason(),
                    );
                }
            }
        }else{
            
            $result = array(
                'status'      => false,
                'status_code' => $response->status(),
                'message'     => $response->reason(),
                'body'        => $response->body(),
            );
        }

        return  $result;
    }

    // Order Lists

    public function index(Request $request)
    {
        $orders = Order::query();

        // Apply filters
        if ($request->filled('date_from') && $request->filled('time_from')) {
            $dateFrom = Carbon::parse($request->date_from . ' ' . $request->time_from);
            $orders->where('created_at', '>=', $dateFrom);
        }

        if ($request->filled('date_to') && $request->filled('time_to')) {
            $dateTo = Carbon::parse($request->date_to . ' ' . $request->time_to);
            $orders->where('created_at', '<=', $dateTo);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $orders->where('status', $request->status);
        }

        if ($request->filled('origin') && $request->origin !== 'all') {
            $orders->where('origin', $request->origin);
        }

        $orders = $orders->get()->map(function($item){
            $item['order_created_at_format'] = Carbon::parse($item->order_created_at)->format('M d, Y H:i:s');
            $item['status_color'] = 'completed';
            return $item ;
        });

        $data = array( 'orders' => $orders,
                        'title'  => CustomHelper::Get_website_name(). " | Orders" ,
                    );

        return view('orders.index', $data);
    }

    public function order_receipt_pdf($id)
    {

        $token = env('DUKAAN_API_TOKEN'); 

        $url = "https://api.mydukaan.io/api/order/seller/{$id}/order/";

        $headers = [
            'Authorization' => 'Bearer ' . env('DUKAAN_API_TOKEN'), 
            'authority' => 'api.mydukaan.io',
        ];

        $response = Http::withHeaders($headers)->get($url);

        if ($response->successful()) {

            $data = array(
                'orders' => $response->json() ,
            );

            $pdf = Pdf::loadView('orders.PDF.receipt', $data);

            return $pdf->stream('invoice.pdf');

        }else {
            return abort(404);
        }
    }

    public function downloadInvoice()
    {
        $data = [
            'order_id' => '15333412',
            'order_date' => 'Aug 18, 2024 17:45',
            'payment' => 'Cash on delivery',
            'customer' => [
                'name' => 'Ravi Varman',
                'address' => '125, JN Road, Anakaputhur, Kanchipuram, Tamil Nadu, IN, 600070',
                'mobile' => '+91-7299851536',
            ],
            'items' => [
                ['name' => 'Cold Pressed Groundnut Oil', 'qty' => 1, 'price' => 120.00]
            ],
            'total' => 120.00,
            'delivery' => 'FREE',
        ];

        $pdf = Pdf::loadView('orders.PDF.receipt', $data);

        return $pdf->stream('invoice.pdf');
    }

}
