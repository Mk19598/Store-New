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
use Picqer\Barcode\BarcodeGeneratorPNG;

class ShippingManagementController extends Controller
{
    public function label()
    {
        try
        {

            $orderId = '81844';
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($orderId, $generator::TYPE_CODE_128));

            $data = array(
                'title' => "Shipping Management | " . CustomHelper::Get_website_name() ,
                'orderId' => $orderId,
                'barcode' => $barcode,
                'customerName' => 'Amuthavalli',
                'customerAddress' => '5-a Milagu Mariamman kovil street',
                'customerCity' => 'Ulundurpet',
                'customerPincode' => '606107',
                'customerPhone' => '9486583186 / 9380233365',
                'orderDate' => '2024-06-12 04:58:28',
                'itemCount' => 4,
                'items' => [
                    ['name' => 'Banana Face Pack', 'quantity' => 50, 'unit' => 'gms'],
                    ['name' => 'Pure Henna Powder', 'quantity' => 100, 'unit' => 'gms'],
                    ['name' => 'Pure Indigo Powder', 'quantity' => 100, 'unit' => 'gms'],
                ],
            );

            return view('shipping.label', $data);

        }
        catch(\Throwable $th)
        {

            return view('layouts.404-Page');
        }
    }
}