<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\CustomHelper;
use App\Models\SiteSetting;
use App\Models\Cerenditals;
use App\Models\User;
use App\Models\EnvSetting;

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
                'EnvSettings' => EnvSetting::first(),
        );

            return view('settings.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }
    }


    public function update(Request $request)
    {
        try {

            $siteSetting = SiteSetting::first(); 

            if (!$siteSetting) {
                $siteSetting = new SiteSetting();
            }

            $siteSetting->website_name = $request->input('website_name');

            if ($request->hasFile('website_logo')) {

                if ($siteSetting->website_logo && file_exists(public_path('uploads/' . $siteSetting->website_logo))) {
                    unlink(public_path('uploads/' . $siteSetting->website_logo));
                }

                $file = $request->file('website_logo');

                $fileName = time() . '_' . $file->getClientOriginalName(); 
                $file->move(public_path('uploads/logos/images'), $fileName); 

                $siteSetting->website_logo = 'uploads/logos/images/' . $fileName;
            }

            $siteSetting->save();
            
            return redirect()->back()->with('success', 'Settings updated successfully!');
        } 

        catch (\Throwable $th) {
            return view('layouts.404-Page');
            //throw $th;
        }
    }
}