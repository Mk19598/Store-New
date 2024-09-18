<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukaanBuyer extends Model
{
    use HasFactory;
    
    protected $table = 'dukaan_buyers';

    protected $guarded = array();
    
    public static $rules = array();
}
