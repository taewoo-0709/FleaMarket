<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function selected_payment_method_is_displayed_in_summary()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address'  => '東京都テスト区1-2-3',
        ]);

        $item = Item::factory()->create();

        Payment::factory()->create(['id' => 1, 'payment_method' => 'コンビニ払い']);
        Payment::factory()->create(['id' => 2, 'payment_method' => 'クレジットカード']);

        $this->actingAs($user);

        $this->get(route('purchase.show', ['item_id' => $item->id]))
            ->assertSee('未選択');

        $this->withSession(['payment_id' => 1])
            ->get(route('purchase.show', ['item_id' => $item->id]))
            ->assertSee('コンビニ払い');
    }
}
