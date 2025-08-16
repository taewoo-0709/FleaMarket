<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Order;
use App\Notifications\CodeVerifyNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'postcode',
        'address',
        'building',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {

    }

    public function items()
    {
    return $this->hasMany(Item::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function purchasedItems()
    {
        return $this->hasManyThrough(
            Item::class,
            Order::class,
            'user_id',
            'id',
            'id',
            'item_id'
        );
    }
}
