<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    use HasFactory;
    
    protected $table = 'content_template';

    protected $guarded = array();
    
    public static $rules = array();
}
