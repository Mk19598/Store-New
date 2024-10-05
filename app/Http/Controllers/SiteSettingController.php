<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\CustomHelper;
use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingController extends Controller
{
    
    public function index()
    {
        $SiteSetting = SiteSetting::first();
        $Auth_user = Auth::user();

        $data = array(
            'title'       => CustomHelper::Get_website_name(). " | Settings" ,
            'SiteSetting' => $SiteSetting ,
            'Auth_user'  => $Auth_user ,
        );

        return view('settings.index',$data);
 
    }
}
