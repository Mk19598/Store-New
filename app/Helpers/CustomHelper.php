<?php

namespace App\Helpers;
use Illuminate\Support\Facades\URL; 
use App\Models\SiteSetting;
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
        $website_logo = SiteSetting::query()->pluck('website_logo_url')->map(function($item){
            $item['website_logo'] = URL::to('public/uploads/logos/Standard-store-logo.webp');
        })->first();

        return $website_logo;

    }
}