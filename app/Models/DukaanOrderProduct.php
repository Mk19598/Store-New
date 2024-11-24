<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukaanOrderProduct extends Model
{
    use HasFactory;

    protected $table = 'dukaan_order_products';

    protected $guarded = array();
    
    public static $rules = array();

    protected $casts = [
        'sku' => 'array', 
        'sku_weight_unit' => 'array', 
        'variant_size' => 'array', 
    ];
}
