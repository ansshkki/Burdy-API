<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
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

    protected $with = ['user', 'likes', 'comments'];

    protected $appends = ['current_price', 'is_liked'];

    protected $withCount = ['comments', 'likes'];

    public function getCurrentPriceAttribute()
    {
        $now = Date::now();
        if ($now >= Date::createFromFormat('Y-m-d', $this->expiration_date)) {
            return 0.0;
        }
        $pJson = json_decode($this->periods);
        foreach ($pJson as $period) {
            $date = DateTime::createFromFormat('Y-m-d', $period->date);
            $sale = (float)$period->sale;
            if ($now >= $date) {
                return $this->price * ((100 - $sale) / 100.0);
            }
        }
        return $this->price;
    }

    public function getIsLikedAttribute()
    {
        return $this->likes()->where('user_id', Auth::id())->exists() ? 1 : 0;
    }

    public static function checkDate(){
        $expiredProducts = Product::where('expiration_date', '<' , Date::now());
        foreach ($expiredProducts as $product){
            Storage::delete('public/images/'.basename($product->image_url));
        }
        Product::where('expiration_date', '<' , Date::now())->delete();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
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
}
