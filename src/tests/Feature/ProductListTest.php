<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Condition;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Condition::factory()->count(3)->create();
    }

    /** @test */
    public function all_items_are_displayed()
    {
        Item::factory()->count(3)->create();

        $this->get('/')
            ->assertStatus(200);
    }

    /** @test */
    public function sold_label_is_displayed_for_purchased_items()
    {
        $item = Item::factory()->create();
        $payment = Payment::factory()->create();

        Order::create([
            'user_id' => User::factory()->create()->id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'stripe_payment_id' => 'test_payment_123',
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都千代田区',
            'shipping_building' => 'テストビル',
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Sold');
    }

    /** @test */
    public function own_items_are_not_displayed_in_list()
    {
        $user = User::factory()->create();

        Item::factory()->count(2)->sequence(
            ['user_id' => $user->id, 'title' => '腕時計'],
            ['user_id' => $user->id, 'title' => 'ノートPC']
        )->create();

        $otherUser = User::factory()->create();

        Item::factory()->count(2)->sequence(
            ['user_id' => $otherUser->id, 'title' => 'HDD'],
            ['user_id' => $otherUser->id, 'title' => '玉ねぎ']
        )->create();

        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200)
            ->assertDontSee('腕時計')
            ->assertDontSee('ノートPC')
            ->assertSee('HDD')
            ->assertSee('玉ねぎ');
    }
}