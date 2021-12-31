<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory, HasApiTokens;

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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsTo(Category::class);
    }
}
