<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukaanOrder extends Model
{
    use HasFactory;
    
    protected $table = 'dukaan_orders';

    protected $guarded = array();
    
    public static $rules = array();
}
