<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = array();
    
    public static $rules = array();

    public function trackingLinks()
    {
        return $this->hasMany(ShippingLink::class, 'order_id', 'order_uuid');
    }

    public function PaymentId()
    {
        return $this->hasMany(WoocommerceOrder::class, 'order_id', 'order_id');
    }

}
