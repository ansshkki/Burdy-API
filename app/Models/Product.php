<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
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

    protected $withCount = ['comments','likes'];

    public function currentPrice()
    {
        $now = Date::now();
        if ($now >= Date::createFromFormat('Y-m-d', $this->expiration_date)) {
            return 0.0;
        }
        $pJson = json_decode($this->periods);
        foreach ($pJson as $period) {
            $date = DateTime::createFromFormat('Y-m-d\TH:i:s+', $period->date);
            $sale = (float)$period->sale;
            if ($now >= $date) {
                return $this->price * (1 - $sale);
            }
        }
        return $this->price;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
