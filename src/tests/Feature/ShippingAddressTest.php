<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function updated_shipping_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Payment::factory()->create(['payment_method' => 'コンビニ払い']);

        $this->actingAs($user);

        $newAddress = [
            'shipping_postcode' => '987-6543',
            'shipping_address' => '東京都テスト区4-5-6',
            'shipping_building' => 'テストビル2F',
        ];

        $this->patch(route('purchase.address.update', $item->id), $newAddress)
            ->assertRedirect(route('purchase.show', $item->id));

        $this->get(route('purchase.show', ['item_id' => $item->id]))
            ->assertSee($newAddress['shipping_postcode'])
            ->assertSee($newAddress['shipping_address'])
            ->assertSee($newAddress['shipping_building']);
    }

    /** @test */
    public function purchased_item_has_shipping_address_stored()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = Payment::factory()->create(['payment_method' => 'コンビニ払い']);

        $this->actingAs($user);

        $newAddress = [
            'shipping_postcode' => '987-6543',
            'shipping_address' => '東京都テスト区4-5-6',
            'shipping_building' => 'テストビル2F',
        ];

        $this->patch(route('purchase.address.update', $item->id), $newAddress)
            ->assertRedirect(route('purchase.show', $item->id));

        $this->withSession(['temp_shipping_address' => $newAddress])
            ->post("/purchase/confirm/{$item->id}", ['payment_id' => $payment->id]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postcode' => $newAddress['shipping_postcode'],
            'shipping_address' => $newAddress['shipping_address'],
            'shipping_building' => $newAddress['shipping_building'],
        ]);
    }
}