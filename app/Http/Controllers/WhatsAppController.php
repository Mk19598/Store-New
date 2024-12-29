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
use GuzzleHttp\Client;
use App\Models\ContentTemplate;
use App\Models\EnvSetting;

class WhatsAppController extends Controller
{
    public function __construct()
    {
        $this->client = new Client();
    }

    public function index()
    {
        try {

            $data = array(  'title'  => " WhatsApp | ". CustomHelper::Get_website_name() );
            return view('WhatsApp.Index', $data);

        } catch (\Throwable $th) {
            return view('layouts.error-pages.404-Page');
        }
    }

    public function sendMessageText(Request $request)
    {
        try {

            $request->validate([
                'number' => 'required',
                'message' => 'required',
                'instance_id' => 'required',
            ]);
    
            $accessToken = env('POETS_API_ACCESS_TOKEN');

            $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                'form_params' => [
                    'number' => $request->number,       
                    'type' => 'text',          
                    'message' => $request->message,     
                    'instance_id' => $request->instance_id, 
                    'access_token' => $accessToken,  
                ],
            ]);

            return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                // return json_decode($e->getResponse()->getBody()->getContents(), true);
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    }   

    public function OrderRejected(Request $request)
    {

        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',1)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                        "{total}",
                        "{orderno}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                        $singleOrder->total_cost, 
                        $singleOrder->order_id, 
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);
                    
                    $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
                        'number' => '91' . $singleOrder->buyer_mobile_number,
                        'type' => 'text',
                        'message' => html_entity_decode($personalized_message),
                        'instance_id' => $INSTANCE_ID,
                        'access_token' => $accessToken,
                    ]);

                                     
                    // $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                    //     'form_params' => [
                    //         'number' => '91' . $singleOrder->buyer_mobile_number, 
                    //         'type' => 'text',          
                    //         'message' => html_entity_decode($personalized_message),     
                    //         'instance_id' => $INSTANCE_ID, 
                    //         'access_token' => $accessToken,  
                    //     ],
                    // ]);
                
                }
                    return $response->getBody()->getContents();

     
            

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    }   
    public function OrderCancelledbyCustomer(Request $request)
    {

        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',2)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                        "{total}",
                        "{orderno}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                        $singleOrder->total_cost, 
                        $singleOrder->order_id, 
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);
                                     
                    $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                        'form_params' => [
                            'number' => '91' . $singleOrder->buyer_mobile_number, 
                            'type' => 'text',          
                            'message' => html_entity_decode($personalized_message),     
                            'instance_id' => $INSTANCE_ID, 
                            'access_token' => $accessToken,  
                        ],
                    ]);
                
                }
                    return $response->getBody()->getContents();

     
            

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    } 

    public function OrderDelivered(Request $request)
    {

        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',3)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                        "{orderno}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                        $singleOrder->order_id, 
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);
        
                    $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
                        'number' => '91' . $singleOrder->buyer_mobile_number,
                        'type' => 'text',
                        'message' => html_entity_decode($personalized_message),
                        'instance_id' => $INSTANCE_ID,
                        'access_token' => $accessToken,
                    ]);

                    // $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                    //     'form_params' => [
                    //         'number' => '91' . $singleOrder->buyer_mobile_number, 
                    //         'type' => 'text',          
                    //         'message' => html_entity_decode($personalized_message),     
                    //         'instance_id' => $INSTANCE_ID, 
                    //         'access_token' => $accessToken,  
                    //     ],
                    // ]);
                
                }
                    return $response->getBody()->getContents();

     
            

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    } 

    public function OrderShipped(Request $request)
    {
        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',4)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                        "{orderno}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                        $singleOrder->order_id, 
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);
                                     
                    $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
                        'number' => '91' . $singleOrder->buyer_mobile_number,
                        'type' => 'text',
                        'message' => html_entity_decode($personalized_message),
                        'instance_id' => $INSTANCE_ID,
                        'access_token' => $accessToken,
                    ]);

                    // $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                    //     'form_params' => [
                    //         'number' => '91' . $singleOrder->buyer_mobile_number, 
                    //         'type' => 'text',          
                    //         'message' => html_entity_decode($personalized_message),     
                    //         'instance_id' => $INSTANCE_ID, 
                    //         'access_token' => $accessToken,  
                    //     ],
                    // ]);
                
                }
                    return $response->getBody()->getContents();

     
            

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    } 

    public function CartAbandonment(Request $request)
    {

        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',5)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);
                               
                    $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
                        'number' => '91' . $singleOrder->buyer_mobile_number,
                        'type' => 'text',
                        'message' => html_entity_decode($personalized_message),
                        'instance_id' => $INSTANCE_ID,
                        'access_token' => $accessToken,
                    ]);

                    // $response = $this->client->post('https://app.poetsmediagroup.com/api/send', [
                    //     'form_params' => [
                    //         'number' => '91' . $singleOrder->buyer_mobile_number, 
                    //         'type' => 'text',          
                    //         'message' => html_entity_decode($personalized_message),     
                    //         'instance_id' => $INSTANCE_ID, 
                    //         'access_token' => $accessToken,  
                    //     ],
                    // ]);
                
                }
                    return $response->getBody()->getContents();

     
            

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    } 

    public function NewOrderReceived(Request $request)
    {
        try {

            $order = Order::wherein('id', [1,2,3])->get();

            $EnvSetting = EnvSetting::first(); 
            

                $template = ContentTemplate::where('id','=',6)->first(); 
                $template_description = $template->template_content ;
                $accessToken = $EnvSetting->POETS_API_ACCESS_TOKEN;
                $INSTANCE_ID = $EnvSetting->POETS_API_INSTANCE_ID;

                foreach ($order as $singleOrder) {
                    $template_change = [
                        "{Name}",
                        "{total}",
                        "{orderno}",
                    ];
                
                    $template_content = [
                        $singleOrder->buyer_first_name.' '.$singleOrder->buyer_last_name,      
                        $singleOrder->total_cost, 
                        $singleOrder->order_id, 
                    ];
                
                    $personalized_message = str_replace($template_change, $template_content, $template_description);

                    $response = Http::asForm()->post('https://app.poetsmediagroup.com/api/send', [
                        'number' => '91' . $singleOrder->buyer_mobile_number,
                        'type' => 'text',
                        'message' => html_entity_decode($personalized_message),
                        'instance_id' => $INSTANCE_ID,
                        'access_token' => $accessToken,
                    ]);

                }
            return $response->getBody()->getContents();
        

            // return back()->with('success', 'Your message has been sent successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return back()->with('success', json_decode($e->getResponse()->getBody()->getContents(), true));
            }
            
            return back()->with('success', 'Request failed');
        }
    } 
}