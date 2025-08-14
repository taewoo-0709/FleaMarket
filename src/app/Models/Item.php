<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'image_url',
        'title',
        'brand',
        'item_explain',
        'price',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'item_id', 'user_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
