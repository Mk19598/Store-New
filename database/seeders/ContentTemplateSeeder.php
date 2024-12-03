<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContentTemplate;
use Carbon\Carbon;

class ContentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentTemplate::truncate();

        $ContentTemplate = [
                        [   'template_type' => 'Order Rejected', 
                            'template_subject' => 'Order Rejected',
                            'template_content' => 'Dear {Name},&nbsp;
                                            Your Order is Now *REJECTED*.Please reply for Further Clarification.&nbsp;
                                            Your Order Amount was {total} &nbsp; *Order ID:*: {orderno} &nbsp;*Order URL:* {orderurl}&nbsp;.
                                            Thank you.&nbsp;',
                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                        [   'template_type' => 'Order Cancelled by Customer', 
                            'template_subject' => 'Order Cancelled by Customer',
                            'template_content' => 'Dear {Name},&nbsp;
                                             Your Order for amount {total} is Now *CANCELLED*.
                                             Please reply to this chat, if you need any further clarification &nbsp;*Order ID:* {orderno}&nbsp;*Order URL:* &nbsp;{orderurl} &nbsp;.
                                             Thank you.&nbsp;',

                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                        [   'template_type' => 'Order Delivered', 
                            'template_subject' => 'Order Delivered',
                            'template_content' => 'Dear {Name},
                                            Your Order is Now *DELIVERED*
                                             &nbsp; *Order ID:* {orderno} &nbsp; *Order URL:* &nbsp; {orderurl} &nbsp;
                                             Thank you.&nbsp;',
                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                        [   'template_type' => 'Order Shipped', 
                            'template_subject' => 'Order Shipped',
                            'template_content' => 'Dear {Name},
                            Your Order is Now SHIPPED. &nbsp; *Order ID:* &nbsp;{orderno} &nbsp;Wait for your Order to Arrive in the Next Few Days&nbsp;*Order URL:* &nbsp;{orderurl}&nbsp;
                             Thank you.&nbsp;',
                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                        [   'template_type' => 'Cart Abandonment', 
                            'template_subject' => 'Cart Abandonment',
                            'template_content' => "Dear {Name},
                                Your Cart misses you. We've saved your items for a limited time. Checkout now&nbsp;*Order URL:* &nbsp;{orderurl}&nbsp;
                                Thank you.&nbsp ;",
                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                        [   'template_type' => 'New Order Received', 
                            'template_subject' => 'New Order Received',
                            'template_content' => "Dear {Name},
                                Thank you for shoping at StandardOil Online Store and purchasing with us. Your Bill Amount is *{total}* &nbsp;*Order ID:* {orderno} &nbsp;*Order Tracking:* &nbsp;{orderurl}&nbsp;Thank you. Please visit again.&nbsp;*Standard Oil*
                                ",
                            'role_type' => 'General Content Triggers' ,
                            'created_at' => Carbon::now(),
                            'updated_at' => null,
                        ],

                ];

                ContentTemplate::insert($ContentTemplate);

    }
}
