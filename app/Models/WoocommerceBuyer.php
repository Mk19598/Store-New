<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceBuyer extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_buyers';

    protected $guarded = array();
    
    public static $rules = array();
}
