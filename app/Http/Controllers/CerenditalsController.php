<?php

namespace App\Http\Controllers;

use App\Models\Cerenditals;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\CustomHelper;

class CerenditalsController extends Controller
{
    public function update(Request $request)
    {
        try {

            Cerenditals::first()->update($request->all());

            $data = array(
                'title' => "Setting | ". CustomHelper::Get_website_name() ,
                'today' => Carbon::today()->format('Y-m-d') ,
            );
    
            return view('settings.index',$data);

        } catch (\Throwable $th) {

            return view('layouts.404-Page');
        }

    }
}