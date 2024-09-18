<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukaanShipping extends Model
{
    use HasFactory;
    
    protected $table = 'dukaan_shippings';

    protected $guarded = array();
    
    public static $rules = array();
}
