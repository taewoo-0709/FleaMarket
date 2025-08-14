<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserInfoGetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_profile_displays_correct_information()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'avatar' => UploadedFile::fake()->image('avatar.jpg')->store('avatars', 'public'),
        ]);

        $payment = Payment::factory()->create(['payment_method' => 'コンビニ払い']);

        Item::factory()->create([
            'user_id' => $user->id,
            'title' => '出品商品1'
        ]);
        Item::factory()->create([
            'user_id' => $user->id,
            'title' => '出品商品2'
        ]);

        $purchaseItem = Item::factory()->create(['title' => '購入商品']);
        Order::create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
            'payment_id' => $payment->id,
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都テスト区1-1-1',
        ]);

        $this->actingAs($user)
            ->get('/mypage?page=sell')
            ->assertSee('テストユーザー')
            ->assertSee(basename($user->avatar))
            ->assertSee('出品商品1')
            ->assertSee('出品商品2')
            ->assertDontSee('購入商品');

        $this->actingAs($user)
            ->get('/mypage?page=buy')
            ->assertSee('購入商品')
            ->assertDontSee('出品商品1');
    }
}