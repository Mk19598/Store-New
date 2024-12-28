<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use App\Helpers\CustomHelper;
use App\Models\InventoryManagement;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\WoocommerceOrder;
use App\Models\WoocommerceBuyer;
use App\Models\WoocommerceShipping;
use App\Models\WoocommerceOrderProduct;
use App\Models\Credentials;

class InventoryManagementController extends Controller
{
    public function Index()
    {
        try{

            // if(InventoryManagement::where('status',1)->count() > 0){
            //     $Dukaan_API_TOKEN = env('DUKAAN_API_TOKEN');
            

            //     $response = Http::withHeaders([
            //         'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
            //         'Accept' => 'application/json',
            //     ])->patch('https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/111a25c7-1eef-45dd-9379-bda76ebf2821/', [
            //         'inventory_list' => [
            //             [
            //                 'warehouse' => 42097,
            //                 'quantity_available' => InventoryManagement::where('status',1)->count()
            //             ]
            //         ]
            //     ]);
            // }                      
           
            $data = array(
                'title' => "Inventory Management | " . CustomHelper::Get_website_name() ,
                'inventory_count' => InventoryManagement::count() ,
                'inventory_data' => InventoryManagement::get() ,
            );

            return view('inventory.index', $data);

        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function create()
    {
        try{
            return view('inventory.create');
        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate(['product_name' => 'required', 'weight' => 'required', 'sku' => 'required', 'barcode' => 'required', ]);

            $woocommerce_Credentials = Credentials::first();

            $woocommerce = new Client(
                $woocommerce_Credentials->woocommerce_url,
                $woocommerce_Credentials->woocommerce_customer_key,
                $woocommerce_Credentials->woocommerce_secret_key,
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                ]
            );

            $products = $woocommerce->get('products');
            
            $skuToFind = $validated['sku'];
            
            $collection = collect($products);
            
            $product = $collection->firstWhere('sku', $skuToFind);
            
            if ($product) {

                $productId = $product->id; 

                
                if(!empty($request->inventory) && $request->inventory == 'on' ){
                    $data = [
                        'stock_status' => 'instock' 
                    ];
                    $validated['inventory'] = 1;
                }else {
                    $data = [
                        'stock_status' => 'outofstock' 
                    ];
                    $validated['inventory'] = 0;
                }

                // if($validated['inventory'] > 0 ){
                //     $data = [
                //         'stock_status' => 'instock' 
                //     ];
                // }elseif ($validated['inventory'] == 0 || $validated['inventory'] == null){
                //     $data = [
                //         'stock_status' => 'outofstock' 
                //     ];
                // }else {
                //     $data = [
                //         'stock_status' => 'outofstock' 
                //     ];
                // }
            
                $updatedProduct = $woocommerce->put("products/{$productId}", $data);
            
            } else {
                return redirect()->route('inventory.index')
                ->with('success', 'Product not found! Invalid SKU ID');
            }

            $generator = new BarcodeGeneratorPNG();
            $barcodeData = $generator->getBarcode($validated['barcode'], $generator::TYPE_CODE_128);

            $barcodeFileName = 'barcode-' . $validated['barcode'] . '.png';
            Storage::put('public/barcodes/' . $barcodeFileName, $barcodeData);

            $validated['barcode_image'] = $barcodeFileName;

            $validated['status'] = 1;

            InventoryManagement::create($validated);

            return redirect()->route('inventory.index')->with('success', 'Inventory created successfully!');

        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function edit($id)
    {
        try{

            $inventory = InventoryManagement::findOrFail($id);
            return view('inventory.edit', compact('inventory'));
        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function update(Request $request, $id)
    {
        try{

            $validated = $request->validate(['product_name' => 'required', 'weight' => 'required', 'sku' => 'required',  'barcode' => 'required', ]);

            $woocommerce_Credentials = Credentials::first();

            $woocommerce = new Client(
                $woocommerce_Credentials->woocommerce_url,
                $woocommerce_Credentials->woocommerce_customer_key,
                $woocommerce_Credentials->woocommerce_secret_key,
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                ]
            );

            $products = $woocommerce->get('products');
            
            $skuToFind = $validated['sku'];
            
            $collection = collect($products);
            
            $product = $collection->firstWhere('sku', $skuToFind);
            if ($product) {

                $productId = $product->id; 

                if(!empty($request->inventory) && $request->inventory == 'on' ){
                    $data = [
                        'stock_status' => 'instock' 
                    ];
                    $validated['inventory'] = 1;
                }else {
                    $data = [
                        'stock_status' => 'outofstock' 
                    ];
                    $validated['inventory'] = 0;
                }
            
                $updatedProduct = $woocommerce->put("products/{$productId}", $data);
            
            } else {
                return redirect()->route('inventory.index')
                ->with('success', 'Product not found! Invalid SKU ID');
            }
            

            $inventory = InventoryManagement::findOrFail($id);

            if ($inventory->barcode !== $validated['barcode'])
            {

                $generator = new BarcodeGeneratorPNG();
                $barcodeData = $generator->getBarcode($validated['barcode'], $generator::TYPE_CODE_128);

                $barcodeFileName = 'barcode-' . $validated['barcode'] . '.png';

                Storage::put('public/barcodes/' . $barcodeFileName, $barcodeData);

                $validated['barcode_image'] = $barcodeFileName;

            }
            else
            {
                $validated['barcode_image'] = $inventory->barcode_image;
            }

            $inventory->update($validated);

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory updated successfully.');

        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }

    }

    public function destroy($id)
    {
        try
        {
            $inventory = InventoryManagement::findOrFail($id)->delete();

            return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function updateStatus(Request $request)
    {
        $inventory = InventoryManagement::find($request->id);
        
        $woocommerce_Credentials = Credentials::first();

        $woocommerce = new Client(
            $woocommerce_Credentials->woocommerce_url,
            $woocommerce_Credentials->woocommerce_customer_key,
            $woocommerce_Credentials->woocommerce_secret_key,
            [
                'wp_api' => true,
                'version' => 'wc/v3',
            ]
        );

        $products = $woocommerce->get('products');
        
        $skuToFind = $inventory->sku;
        
        $collection = collect($products);
        
        $product = $collection->firstWhere('sku', $skuToFind);
        if ($product) {

            $productId = $product->id; 

            if(!empty($request->status) && $request->inventory == 1 ){
                $data = [
                    'stock_status' => 'instock' 
                ];
            }else {
                $data = [
                    'stock_status' => 'outofstock' 
                ];
            }
        
            $updatedProduct = $woocommerce->put("products/{$productId}", $data);
        
        } else {

            return response()->json(['error' => 'Product not found! Invalid SKU ID']);
            
        }

        if ($inventory) {
            $inventory->inventory = $request->status;
            $inventory->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}