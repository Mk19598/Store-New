<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        try {
            $validated = $request->validate([
                'product_name' => 'required',
                'sku' => 'required',
                'barcode' => 'required',
                'dukaan_sku' => 'required',
            ]);

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

            try {
                $response = $woocommerce->get('products', [
                    'sku' => $validated['sku']
                ]);
            
            if (!empty($response)) {
                    $product = $response[0]; 
                } else {
                    echo "No product found with SKU";
                }
            } catch (HttpClientException $e) {
                echo "Error: " . $e->getMessage();
            }

            $Dukaan_API_TOKEN = Credentials::pluck('dukkan_api_token')->first();
            $storeId =  CustomHelper::StoreId();
            $dukaanSkuCode = $validated['dukaan_sku'];

            $dukaanProducts = [];
            $currentPage = 1;

            do {
                $dukaanResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Accept' => 'application/json',
                ])->get("https://api.mydukaan.io/api/product/seller/{$storeId}/product/v2/?page={$currentPage}&pop_fields=variants_data");

                $currentProducts = $dukaanResponse->json();

                if (!isset($currentProducts['results'])) {
                    $data = array('err_msg' => 'Unexpected response structure Invaild StoreID',);
                    return view('layouts.error-pages.500-Page',$data);
                }

                $dukaanProducts = array_merge($dukaanProducts, $currentProducts['results']);

                $hasMorePages = !empty($currentProducts['next']);

                $currentPage++;

            } while ($hasMorePages);


            // $dukaanResponse = Http::withHeaders([
            //     'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
            //     'Accept' => 'application/json',
            // ])->get("https://api.mydukaan.io/api/product/seller/{$storeId}/product/v2/?page=1&pop_fields=variants_data");

            // $dukaanProducts = $dukaanResponse->json();
            //     dd($dukaanProducts);
            // if (!is_array($dukaanProducts)) {
            //     return redirect()->route('inventory.index')
            //         ->with('error', 'Unexpected response format from Dukaan.');
            // }

            $dukaanProductsData = $dukaanProducts['data'] ?? $dukaanProducts['results'] ?? $dukaanProducts;

            if (!is_array($dukaanProductsData)) {
                return redirect()->route('inventory.index')
                    ->with('error', 'Dukaan product data is not an array.');
            }

            $filteredProduct = array_filter($dukaanProductsData, function ($dukaanproduct) use ($dukaanSkuCode) {
                foreach ($dukaanproduct['skus'] as $sku) {
                    if ($sku['sku_code'] === $dukaanSkuCode) {
                        return true;
                    }
                }
                return false;
            });

            $filteredProduct = array_values($filteredProduct);
            $dukaanProduct = $filteredProduct[0] ?? null;

            // Check for SKU mismatch
            if (!$product && !$dukaanProduct) {
                return redirect()->route('inventory.index')
                    ->with('success', 'SKU mismatch between Dukaan and WooCommerce.');
            }

            if ($product && !$dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                    $validated['inventory'] = 1;
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                    $validated['inventory'] = 0;
                }
        
                $productId = $product->id;
                $productparentId = $product->parent_id;

                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }                $message = 'SKU mismatch Failed to update Dukaan inventory.';
                $validated['dukaan_sku'] = null;
                
            }

            if (!$product && $dukaanProduct) {

                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];
                $warehouseInventoryId = $sku['uuid'];

                $quantity_available = (!empty($request->inventory) && $request->inventory == 'on') ? 100 : 0 ;

                $validated['inventory'] = (!empty($request->inventory) && $request->inventory == 'on') ? 1 : 0 ;

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);


                $message = 'SKU mismatch Failed to update WooCommerce inventory.';
                $validated['sku'] = null;
                
            }
            

            if ($product && $dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                    $validated['inventory'] = 1;
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                    $validated['inventory'] = 0;
                }
        
                $productId = $product->id;
                $productparentId = $product->parent_id;
                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }
                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];
                $warehouseInventoryId = $sku['uuid'];

                $quantity_available = (!empty($request->inventory) && $request->inventory == 'on') ? 100 : 0 ;

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);


                $message = 'updated Dukaan and Woocommerce inventory.';
                
            }

            $generator = new BarcodeGeneratorPNG();
            $barcodeData = $generator->getBarcode($validated['barcode'], $generator::TYPE_CODE_128);
            $barcodeFileName = 'barcode-' . $validated['barcode'] . '.png';
            Storage::put('public/barcodes/' . $barcodeFileName, $barcodeData);

            $validated['barcode_image'] = $barcodeFileName;
            $validated['status'] = 1;

            InventoryManagement::create($validated);

            return redirect()->route('inventory.index')->with('success', 'Inventory created successfully!'.$message);

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
        try {
            $validated = $request->validate([
                'product_name' => 'required',
                'sku' => 'required',
                'barcode' => 'required',
                'dukaan_sku' => 'required',
            ]);

            $woocommerce_Credentials = Credentials::first();

            $woocommerce = new Client(
                $woocommerce_Credentials->woocommerce_url, 
                $woocommerce_Credentials->woocommerce_customer_key,       
                $woocommerce_Credentials->woocommerce_secret_key,    
                ['wp_api' => true,'version' => 'wc/v3' ]
            );
            try {
                $response = $woocommerce->get('products', [
                    'sku' => $validated['sku']
                ]);
            
            if (!empty($response)) {
                    $product = $response[0]; 
                } else {
                    echo "No product found with SKU";
                }
            } catch (HttpClientException $e) {
                echo "Error: " . $e->getMessage();
            }
            
            $Dukaan_API_TOKEN = Credentials::pluck('dukkan_api_token')->first();
            $storeId =  CustomHelper::StoreId();
            $dukaanSkuCode = $validated['dukaan_sku'];

            
            $dukaanProducts = [];
            $currentPage = 1;

            do {
                $dukaanResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Accept' => 'application/json',
                ])->get("https://api.mydukaan.io/api/product/seller/{$storeId}/product/v2/?page={$currentPage}&pop_fields=variants_data");

                $currentProducts = $dukaanResponse->json();

                if (!isset($currentProducts['results'])) {
                    $data = array('err_msg' => 'Unexpected response structure Invaild StoreID',);
                    return view('layouts.error-pages.500-Page',$data);
                }

                $dukaanProducts = array_merge($dukaanProducts, $currentProducts['results']);

                $hasMorePages = !empty($currentProducts['next']);

                $currentPage++;

            } while ($hasMorePages);


            if (!is_array($dukaanProducts)) {
                return redirect()->route('inventory.index')
                    ->with('error', 'Unexpected response format from Dukaan.');
            }

            $dukaanProductsData = $dukaanProducts['data'] ?? $dukaanProducts['results'] ?? $dukaanProducts;

            if (!is_array($dukaanProductsData)) {
                return redirect()->route('inventory.index')
                    ->with('error', 'Dukaan product data is not an array.');
            }

            $filteredProduct = array_filter($dukaanProductsData, function ($dukaanproduct) use ($dukaanSkuCode) {
                foreach ($dukaanproduct['skus'] as $sku) {
                    if ($sku['sku_code'] === $dukaanSkuCode) {
                        return true;
                    }
                }
                return false;
            });

            $filteredProduct = array_values($filteredProduct);
            $dukaanProduct = $filteredProduct[0] ?? null;

            // Check for SKU mismatch
            if (!$product && !$dukaanProduct) {
                return redirect()->route('inventory.index')
                    ->with('success', 'SKU mismatch between Dukaan and WooCommerce.');
            }

            if ($product && !$dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                    $validated['inventory'] = 1;
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                    $validated['inventory'] = 0;
                }
        
                $productId = $product->id;
                $productparentId = $product->parent_id;
                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }
                $message = 'SKU mismatch Failed to update Dukaan inventory.';
                $validated['dukaan_sku'] = null;
                
            }

            if (!$product && $dukaanProduct) {

                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];

                $quantity_available = (!empty($request->inventory) && $request->inventory == 'on') ? 100 : 0 ;

                $validated['inventory'] = (!empty($request->inventory) && $request->inventory == 'on') ? 1 : 0 ;

                $warehouseInventoryId = $sku['uuid'];

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);

                $message = 'SKU mismatch Failed to update WooCommerce inventory.';
                $validated['sku'] = null;
                
            }
            

            if ($product && $dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                    $validated['inventory'] = 1;
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                    $validated['inventory'] = 0;
                }
        
                $productId = $product->id;
                $productId = $product->id;
                $productparentId = $product->parent_id;
                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }
                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];
                $warehouseInventoryId = $sku['uuid'];

                $quantity_available = (!empty($request->inventory) && $request->inventory == 'on') ? 100 : 0 ;

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);


                $message = 'updated Dukaan and Woocommerce inventory.';
                
            }

            $inventory = InventoryManagement::findOrFail($id);

           
            if ($inventory->barcode !== $validated['barcode'] || empty($inventory->barcode_image))
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

            $validated['status'] = 1;

            $inventory->update($validated);

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory updated successfully.'.$message);

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

            try {
                $response = $woocommerce->get('products', [
                    'sku' => $validated['sku']
                ]);
            
            if (!empty($response)) {
                    $product = $response[0]; 
                } else {
                    echo "No product found with SKU";
                }
            } catch (HttpClientException $e) {
                echo "Error: " . $e->getMessage();
            }

            $Dukaan_API_TOKEN = Credentials::pluck('dukkan_api_token')->first();
            $storeId =  CustomHelper::StoreId();
            $dukaanSkuCode = $validated['dukaan_sku'];

            
            $dukaanProducts = [];
            $currentPage = 1;

            do {
                $dukaanResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Accept' => 'application/json',
                ])->get("https://api.mydukaan.io/api/product/seller/{$storeId}/product/v2/?page={$currentPage}&pop_fields=variants_data");

                $currentProducts = $dukaanResponse->json();

                if (!isset($currentProducts['results'])) {
                    $data = array('err_msg' => 'Unexpected response structure Invaild StoreID',);
                    return view('layouts.error-pages.500-Page',$data);
                }

                $dukaanProducts = array_merge($dukaanProducts, $currentProducts['results']);

                $hasMorePages = !empty($currentProducts['next']);

                $currentPage++;

            } while ($hasMorePages);


            if (!is_array($dukaanProducts)) {
                return redirect()->route('inventory.index')
                    ->with('error', 'Unexpected response format from Dukaan.');
            }

            $dukaanProductsData = $dukaanProducts['data'] ?? $dukaanProducts['results'] ?? $dukaanProducts;

            if (!is_array($dukaanProductsData)) {
                return redirect()->route('inventory.index')
                    ->with('error', 'Dukaan product data is not an array.');
            }

            $filteredProduct = array_filter($dukaanProductsData, function ($dukaanproduct) use ($dukaanSkuCode) {
                foreach ($dukaanproduct['skus'] as $sku) {
                    if ($sku['sku_code'] === $dukaanSkuCode) {
                        return true;
                    }
                }
                return false;
            });

            $filteredProduct = array_values($filteredProduct);
            $dukaanProduct = $filteredProduct[0] ?? null;

            // Check for SKU mismatch
            if (!$product && !$dukaanProduct) {
                return redirect()->route('inventory.index')
                    ->with('success', 'SKU mismatch between Dukaan and WooCommerce.');
            }

            if ($product && !$dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                    $validated['inventory'] = 1;
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                    $validated['inventory'] = 0;
                }
        
                $productId = $product->id;
                $productparentId = $product->parent_id;
                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }
                $message = 'SKU mismatch Failed to update Dukaan inventory.';
                $validated['dukaan_sku'] = null;
                
            }

            if (!$product && $dukaanProduct) {

                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];

                $quantity_available = (!empty($request->status) && $request->status == 'on') ? 100 : 0 ;

                $warehouseInventoryId = $sku['uuid'];

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);

                $message = 'SKU mismatch Failed to update WooCommerce inventory.';
                $validated['sku'] = null;
                
            }
            

            if ($product && $dukaanProduct) {

                if (!empty($request->inventory) && $request->inventory == 'on') {
                    $data = [
                        'stock_status' => 'instock'
                    ];
                } else {
                    $data = [
                        'stock_status' => 'outofstock'
                    ];
                }
        
                $productId = $product->id;
                $productparentId = $product->parent_id;
                if($productparentId > 0 ){
                    $woocommerce->put("products/{$productparentId}/variations/{$productId}", $data);
                }else{
                    $woocommerce->put("products/{$productId}/", $data);
                }
                $sku = null;
                foreach ($dukaanProduct['skus'] as $item) {
                    if ($item['sku_code'] === $dukaanSkuCode) {
                        $sku = $item;
                        break;
                    }
                }

                if (!$sku || !isset($sku['warehouse_inventory_items'])) {
                    return redirect()->route('inventory.index')
                        ->with('error', 'No warehouse inventory items found for Dukaan SKU.');
                }

                $warehouseInventoryItems = $sku['warehouse_inventory_items'];
                $warehouseInventoryId = $sku['uuid'];

                $quantity_available = (!empty($request->status) && $request->status == 'on') ? 100 : 0 ;

                $inventoryList = array_map(function ($item) use ($quantity_available) {
                    return [
                        'warehouse' => $item['warehouse_id'], 
                        'quantity_available' => (string) $quantity_available, 
                    ];
                }, $warehouseInventoryItems);

                $payload = [
                    'inventory_list' => $inventoryList,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $Dukaan_API_TOKEN,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->patch("https://api.mydukaan.io/api/store/seller/seller-warehouse-inventory/{$warehouseInventoryId}/", $payload);


                $message = 'updated Dukaan and Woocommerce inventory.';
                
            }


        if ($inventory) {
            $inventory->inventory = $request->status;
            $inventory->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function barcode_edit($id)
    {
        try{

            $inventory = InventoryManagement::findOrFail($id);
            return view('inventory.barcode_edit', compact('inventory'));
        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }
    
    public function barcode_update(Request $request, $id)
    {
        try {
            
            $validated = $request->validate([
                'barcode' => 'required',
            ]);

            $inventory = InventoryManagement::findOrFail($id);

           
            if ($inventory->barcode !== $validated['barcode'] || empty($inventory->barcode_image))
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
            return $th;
        }
    }


    
    public function autoGenrateBarcode(Request $request)
    {
        try {
            
            $inventory = InventoryManagement::where('barcode_image' , null)->where('barcode','!=',null)->get();
            if ($inventory->isEmpty()) {
                return redirect()->route('inventory.index')
                ->with('success', 'No records found that require barcode generation.');
            }

            $generator = new BarcodeGeneratorPNG();

            foreach ($inventory as $item) {
                $barcodeData = $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128);
                $barcodeFileName = 'barcode-' . $item->barcode . '.png';
                Storage::put('public/barcodes/' . $barcodeFileName, $barcodeData);
                $item->barcode_image = $barcodeFileName;
                $item->save();
            }

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory Barcode updated successfully.');

        }
        catch(\Throwable $th)
        {
            return $th;
        }
    }

}