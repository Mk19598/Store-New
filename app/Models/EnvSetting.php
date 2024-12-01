<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvSetting extends Model
{
    use HasFactory;
    
    protected $table = 'env_settings';

    protected $guarded = array();
    
    public static $rules = array();
}
