<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    } 
}
