<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Helpers\CustomHelper;
use App\Models\SiteSetting;
use App\Models\Credentials;
use App\Models\User;
use App\Models\EnvSetting;

class SiteSettingController extends Controller
{
    public function index()
    {
        try {

            $SiteSetting = SiteSetting::first();
            $Auth_user = Auth::user();
            $Credentials = Credentials::first();

            $data = array(
                'title'       => "Settings | ".CustomHelper::Get_website_name() ,
                'SiteSetting' => $SiteSetting ,
                'Auth_user'   => $Auth_user ,
                'Credentials' => $Credentials ,
                'EnvSettings' => EnvSetting::first(),
        );

            return view('settings.index',$data);

        } catch (\Throwable $th) {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function update(Request $request)
    {
        try {

            $SiteSetting = SiteSetting::first();

            $inputs = array( 'website_name' => $request->website_name);

            if($request->hasFile('website_logo')){

                $file = $request->website_logo;

                if (File::exists(base_path('public/uploads/Logos/'.$SiteSetting->website_logo))) {
                    File::delete(base_path('public/uploads/Logos/'.$SiteSetting->website_logo));
                }
    
                $filename = 'site-Logo.'.$file->getClientOriginalExtension();

                $file->move(public_path('uploads/Logos'), $filename);

                $inputs +=  ['website_logo' => $filename ];
            }

            $SiteSetting->update($inputs);

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } 
        catch (\Throwable $th) {
            return view('layouts.error-pages.404-Page');
        }
    }
}