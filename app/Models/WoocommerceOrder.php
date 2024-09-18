<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceOrder extends Model
{
    use HasFactory;
    
    protected $table = 'woocommerce_orders';

    protected $guarded = array();
    
    public static $rules = array();
}
