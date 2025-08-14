<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Condition;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Condition::factory()->count(3)->create();
    }

    /** @test */
    public function only_liked_items_are_displayed()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create(['title' => 'LIKED_ITEM_TITLE']);
        Item::factory()->create(['title' => 'UNLIKED_ITEM_TITLE']);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user)
            ->get('/?tab=mylist')
            ->assertStatus(200)
            ->assertSee('LIKED_ITEM_TITLE')
            ->assertDontSee('UNLIKED_ITEM_TITLE');
    }

    /** @test */
    public function sold_label_is_displayed_for_purchased_items()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = Payment::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'stripe_payment_id' => 'test_payment_123',
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都千代田区',
            'shipping_building' => 'テストビル',
        ]);

        $this->actingAs($user)
            ->get('/?tab=mylist')
            ->assertStatus(200)
            ->assertSee('Sold')
            ->assertSee($item->title);
    }

    /** @test */
    public function no_items_displayed_for_guest()
    {
        Item::factory()->create();

        $this->get('/?tab=mylist')
            ->assertStatus(200);
    }
}
