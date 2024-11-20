<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingLink extends Model
{
    use HasFactory;

    protected $table = 'shipping_links';

    protected $guarded = array();
    
    public static $rules = array();
}
