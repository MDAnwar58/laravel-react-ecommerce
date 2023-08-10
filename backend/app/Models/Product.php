<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id');
    }
}
