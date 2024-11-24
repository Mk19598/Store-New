<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceOrderProduct extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_order_products';

    protected $guarded = array();
    
    public static $rules = array();
}
