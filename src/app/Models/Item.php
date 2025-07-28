<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillabe = [
        'user_id',
        'category_id',
        'condition_id',
        'image_url',
        'title',
        'brand',
        'item_explain',
        'price',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
}
