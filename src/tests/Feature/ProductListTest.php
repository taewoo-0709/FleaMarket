<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);

        $this->seed();
    }

    public function test_all_items_are_displayed()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_sold_label_is_displayed_for_purchased_items()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = Payment::factory()->create();

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'stripe_payment_id' => 'test_payment_123',
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都千代田区',
            'shipping_building' => 'テストビル',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    public function test_own_items_are_not_displayed_in_list()
    {
        $user = \App\Models\User::find(1);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('腕時計');
        $response->assertDontSee('ノートPC');

        $response->assertSee('HDD');
        $response->assertSee('玉ねぎ');
    }
}
