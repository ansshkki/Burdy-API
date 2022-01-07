<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'product_id', 'user_id', 'user', 'id'];

    protected $appends = ['username'];

    public function getUsernameAttribute()
    {
        return $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
