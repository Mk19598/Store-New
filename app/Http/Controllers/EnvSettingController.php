<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Models\EnvSetting;

class EnvSettingController extends Controller
{
    public function update(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'MAIL_HOST' => 'required|string',
                'MAIL_PORT' => 'required|integer',
                'MAIL_USERNAME' => 'required|string',
                'MAIL_PASSWORD' => 'required|string',
                'MAIL_ENCRYPTION' => 'nullable|string',
                'MAIL_FROM_ADDRESS' => 'nullable|email',
                'MAIL_FROM_NAME' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $mailSetting = EnvSetting::first(); 

            if (!$mailSetting) {
                $mailSetting = new EnvSetting();
            }

            $mailSetting->MAIL_HOST = $request->MAIL_HOST;
            $mailSetting->MAIL_PORT = $request->MAIL_PORT;
            $mailSetting->MAIL_USERNAME = $request->MAIL_USERNAME;
            $mailSetting->MAIL_PASSWORD = $request->MAIL_PASSWORD;
            $mailSetting->MAIL_ENCRYPTION = $request->MAIL_ENCRYPTION;
            $mailSetting->MAIL_FROM_ADDRESS = $request->MAIL_FROM_ADDRESS;
            $mailSetting->MAIL_FROM_NAME = $request->MAIL_FROM_NAME;
            $mailSetting->ADMIN_MAIL = $request->ADMIN_MAIL;
        
            $mailSetting->save();
            
            $this->updateEnv([
                'MAIL_DRIVER' => "smtp",
                'MAIL_HOST' => $request->MAIL_HOST,
                'MAIL_PORT' => $request->MAIL_PORT,
                'MAIL_USERNAME' => $request->MAIL_USERNAME,
                'MAIL_PASSWORD' => $request->MAIL_PASSWORD,
                'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION,
                'MAIL_FROM_ADDRESS' => $request->MAIL_FROM_ADDRESS,
                'MAIL_FROM_NAME' => $request->MAIL_FROM_NAME,
                'ADMIN_MAIL' => $request->ADMIN_MAIL,
            ]);

            return redirect()->back()->with('success', 'Mail configuration updated successfully.');
        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    private function updateEnv(array $data)
    {
        try
            {
                $envPath = base_path('.env');
                $envContent = File::get($envPath);
            
                foreach ($data as $key => $value) {
                    $value = str_contains($value, ' ') ? "\"$value\"" : $value;
            
                    $pattern = "/^{$key}=.*/m";
                    $replacement = "{$key}={$value}";
                    if (preg_match($pattern, $envContent)) {
                        $envContent = preg_replace($pattern, $replacement, $envContent);
                    } else {
                        $envContent .= "\n{$replacement}";
                    }
                }
            
                File::put($envPath, $envContent);
            
                Artisan::call('config:cache');
            }
        catch(\Throwable $th)
            {
                return view('layouts.error-pages.404-Page');
            }
    }

    // public function WhatsAppUpdate(Request $request)
    // {
    //     try{

    //         $request->validate([
    //             'POETS_API_ACCESS_TOKEN' => 'required|string',
    //             'POETS_API_INSTANCE_ID' => 'required|string',
    //         ]);

    //         $WhatsAppSetting = EnvSetting::first(); 

    //         if (!$WhatsAppSetting) {
    //             $WhatsAppSetting = new EnvSetting();
    //         }

    //         $WhatsAppSetting->POETS_API_ACCESS_TOKEN = $request->POETS_API_ACCESS_TOKEN;
    //         $WhatsAppSetting->POETS_API_INSTANCE_ID = $request->POETS_API_INSTANCE_ID;        
    //         $WhatsAppSetting->save();
            
    //         $this->updateEnv([
    //             'POETS_API_ACCESS_TOKEN' => $request->POETS_API_ACCESS_TOKEN,
    //             'POETS_API_INSTANCE_ID' => $request->POETS_API_INSTANCE_ID,
    //         ]);

    //         return redirect()->back()->with('success', 'WhatsApp configuration updated successfully.');

    //     }
    //     catch(\Throwable $th)
    //     {
    //         return view('layouts.error-pages.404-Page');
    //     }
    // }
    
    public function ShippingUpdate(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'Shipping_Username' => 'required|string',
                'Shipping_Password' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $ShippingSetting = EnvSetting::first(); 

            if (!$ShippingSetting) {
                $ShippingSetting = new EnvSetting();
            }

            $ShippingSetting->Shipping_Username = $request->Shipping_Username;
            $ShippingSetting->Shipping_Password = $request->Shipping_Password;        
            $ShippingSetting->save();
            
            return redirect()->back()->with('success', 'Shipping configuration updated successfully.');

        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function StoreIDUpdate(Request $request)
    {
        try{

            $request->validate([
                'storeId' => 'required|string',
            ]);

            $ShippingSetting = EnvSetting::first(); 

            if (!$ShippingSetting) {
                $ShippingSetting = new EnvSetting();
            }

            $ShippingSetting->storeId = $request->storeId;
            $ShippingSetting->save();
            
            return redirect()->back()->with('success', 'storeId configuration updated successfully.');

        }
        catch(\Throwable $th)
        {
            return view('layouts.error-pages.404-Page');
        }
    }
}