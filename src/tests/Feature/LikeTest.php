<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Condition::factory()->count(3)->create();
        Category::factory()->count(3)->create();
    }

    /** @test */
    public function user_can_like_a_product_and_like_count()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('like.toggle', ['item_id' => $item->id]))
            ->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->likedUsers()->count());
    }

    /** @test */
    public function like_icon_has_liked_class()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->postJson(route('like.toggle', ['item_id' => $item->id]));

        $response = $this->actingAs($user)
            ->get(route('items.detail', ['item_id' => $item->id]));

        $response->assertStatus(200)
            ->assertSee('like-button liked')
            ->assertSee('★');
    }

    /** @test */
    public function user_can_unlike_a_product_and_like_count_decreases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('like.toggle', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)
            ->post(route('like.toggle', ['item_id' => $item->id]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->fresh()->likedUsers()->count());

        $this->actingAs($user)
            ->get(route('items.detail', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSee('like-button')
            ->assertSee('☆');
    }
}