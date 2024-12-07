<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credentials extends Model
{
    use HasFactory;
    
    protected $table = 'credentials';

    protected $guarded = array();
    
    public static $rules = array();
}
