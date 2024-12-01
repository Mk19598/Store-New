<?php

namespace App\Helpers;
use Illuminate\Support\Facades\URL; 
use App\Models\SiteSetting;
use App\Models\EnvSetting;
use Carbon\Carbon;

class CustomHelper
{
    public static function Get_website_name()
    {
        $website_name = SiteSetting::query()->pluck('website_name')->first();
        return $website_name;
    }

    public static function Get_website_logo_url()
    {
        $website_logo = SiteSetting::query()->pluck('website_logo')->map(function($item){
            return URL::to('public/'.$item);
        })->first();

        return $website_logo;
    }

    
    public static function Get_user_img_url()
    {
        return URL::to('public/uploads/users-logo.png');
    }

    public static function Shipping_Username()
    {
        $Shipping_Username = EnvSetting::query()->pluck('Shipping_Username')->first();
        return $Shipping_Username;
    }

    public static function Shipping_Password()
    {
        $Shipping_Password = SiteSetting::query()->pluck('Shipping_Password')->first();
        return $Shipping_Password;
    }

}