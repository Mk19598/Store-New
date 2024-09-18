<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukaanProduct extends Model
{
    use HasFactory;

    protected $table = 'dukaan_products';

    protected $guarded = array();
    
    public static $rules = array();
}
