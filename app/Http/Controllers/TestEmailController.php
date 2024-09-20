<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use App\Mail\ContactMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use Mail;

class TestEmailController extends Controller
{

    public function index()
    {
        $data = array( 
                        'title'  => CustomHelper::Get_website_name() . " | Emails" ,
                    );
        return view('email.test', $data);

    }

    public function sendMail(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        ]);
        // Gather details
        $details = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        ];

        Mail::send('email.TestMail', [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,

        ], function($mail) use($request) {
            $mail->from('admin@poetsmediagroup.com', 'Dukaan');
            $mail->to($request->email)->subject('Testing Email');
        });

        return back()->with('success', 'Your message has been sent successfully!');

    }   
}