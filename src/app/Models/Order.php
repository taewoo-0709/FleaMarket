<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Payment;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_id',
        'stripe_payment_id',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}