<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
    }

    /** @test */
    public function user_can_complete_purchase_via_webhook()
    {
        $this->actingAs($this->user);
        $payment = Payment::factory()->create();

        $payload = [
            'id' => 'evt_test',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_session',
                    'payment_intent' => 'pi_test_intent',
                    'metadata' => [
                        'user_id' => $this->user->id,
                        'item_id' => $this->item->id,
                        'payment_id' => $payment->id,
                        'shipping_postcode' => '123-4567',
                        'shipping_address' => '東京都中央区1-1-1',
                        'shipping_building' => 'テストビル',
                    ]
                ]
            ]
        ];

        $this->postJson(route('stripe.webhook'), $payload, ['Stripe-Signature' => 'test_signature'])
            ->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'stripe_payment_id' => 'pi_test_intent'
        ]);
    }

    /** @test */
    public function purchased_item_is_marked_as_sold_on_list()
    {
        Order::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('items.list'))
            ->assertStatus(200)
            ->assertSee('Sold');
    }

    /** @test */
    public function purchased_item_appears_in_profile_purchased_list()
    {
        Order::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $request = Request::create('/mypage/purchased', 'GET', ['page' => 'buy']);
        $request->setUserResolver(fn() => $this->user);

        $controller = new ProfileController();
        $response = $controller->show($request);

        $this->assertStringContainsString($this->item->title, $response->render());
    }
}