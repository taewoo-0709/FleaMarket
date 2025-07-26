<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillabe = [
        'category_name',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
