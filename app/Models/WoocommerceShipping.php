<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceShipping extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_shippings';

    protected $guarded = array();
    
    public static $rules = array();
}
