<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\CustomHelper;
use App\Models\SiteSetting;
use App\Models\Cerenditals;
use App\Models\User;

class SiteSettingController extends Controller
{
    public function index()
    {
        try {

            $SiteSetting = SiteSetting::first();
            $Auth_user = Auth::user();
            $Cerenditals = Cerenditals::first();

            $data = array(
                'title'       => "Settings | ".CustomHelper::Get_website_name() ,
                'SiteSetting' => $SiteSetting ,
                'Auth_user'   => $Auth_user ,
                'Cerenditals' => $Cerenditals ,
            );

            return view('settings.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }
    }
}
