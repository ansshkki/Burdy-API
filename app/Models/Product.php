<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory,HasApiTokens;


    protected $fillable = [
        'name',
        'image_url',
        'category_id',
        'expiration_date',
        'price',
        'periods',
        'quantity',
        'user_id',
        'views',
        'comments',
        'likes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
        
    }
    /*public function likes()
    {
        return $this->hasMany();
        
    }

    public function comments()
    {
        return $this->hasMany();
        
    }*/
    

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
