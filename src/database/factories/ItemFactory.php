<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Condition;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'item_explain' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'condition_id' => Condition::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::factory(),
            'image_url' => 'items/default.jpg',
        ];
    }
}
