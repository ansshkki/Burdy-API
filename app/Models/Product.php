<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    
    
    protected $fillable = [
        'name',
        'image_url',
        'category_id',
        'expiration_date',
        'price',
        'periods',
        'quantity',
        'user_id',
        'description',
        
        
    ];
}
