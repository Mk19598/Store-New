<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Models\Log;

class LogController extends Controller
{
    public function crons_log(Request $request)
    {
        try {

            $crons_log = Log::get()->map(function($item){
                $item['created_at_format'] = Carbon::parse($item->created_at)->format('M d, Y h:i:s A');
                return  $item ;
            });

            $data = array( 'crons_log' => $crons_log, 
                            'title'  => "Logs | ".CustomHelper::Get_website_name()  ,
                            'today' => Carbon::today()->format('Y-m-d') ,
                        );

            return view('Logs.crons-logs', $data);
            
        } catch (\Throwable $th) {
            return view('layouts.error-pages.404-Page');
        }
    }
}
