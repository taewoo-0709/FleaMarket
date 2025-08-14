<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_id' => Payment::factory()->create()->id,
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都中央区1-1-1',
            'shipping_building' => 'テストビル',
        ];
    }
}
