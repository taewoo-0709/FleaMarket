<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillabe = [
        'payment_method',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
