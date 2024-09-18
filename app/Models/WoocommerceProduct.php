<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceProduct extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_products';

    protected $guarded = array();
    
    public static $rules = array();
}
