<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Condition;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'item_explain' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'condition_id' =>  Condition::factory(),
            'image_url' => 'items/default.jpg',
        ];
    }
}
