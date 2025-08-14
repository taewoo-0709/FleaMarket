<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Condition::factory()->count(3)->create();
    }

    /** @test */
    public function can_search_items_by_partial_name()
    {
        $user = User::factory()->create();

        Item::factory()->count(3)->sequence(
            ['title' => 'リンゴジュース', 'user_id' => $user->id],
            ['title' => 'バナナジュース', 'user_id' => $user->id],
            ['title' => 'オレンジジュース', 'user_id' => $user->id]
        )->create();

        $this->get('/?keyword=リンゴ')
            ->assertStatus(200)
            ->assertSee('リンゴジュース')
            ->assertDontSee('バナナジュース')
            ->assertDontSee('オレンジジュース');
    }

    /** @test */
    public function search_keyword_is_retained_on_mylist_tab()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create(['title' => 'リンゴジュース']);
        Item::factory()->create(['title' => 'バナナジュース']);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user)
            ->get('/?tab=mylist&keyword=リンゴ')
            ->assertStatus(200)
            ->assertSee('リンゴジュース')
            ->assertDontSee('バナナジュース')
            ->assertSee('value="リンゴ"', false);
    }
}