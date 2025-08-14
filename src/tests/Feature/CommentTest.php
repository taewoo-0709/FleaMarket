<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->item = Item::factory()->create();
    }

    /** @test */
    public function logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$this->item->id}/comment", ['comment' => 'テストコメントです'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメントです',
        ]);

        $this->assertEquals(1, $this->item->fresh()->comments()->count());
    }

    /** @test */
    public function guest_user_cannot_post_comment()
    {
        $this->post("/item/{$this->item->id}/comment", ['comment' => 'ゲストコメント'])
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', ['comment' => 'ゲストコメント']);
    }

    /** @test */
    public function comment_body_is_required()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$this->item->id}/comment", ['comment' => ''])
            ->assertSessionHasErrors(['comment']);

        $this->assertEquals(0, $this->item->fresh()->comments()->count());
    }

    /** @test */
    public function comment_body_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $longComment = str_repeat('あ', 256);

        $this->actingAs($user)
            ->post("/item/{$this->item->id}/comment", ['comment' => $longComment])
            ->assertSessionHasErrors(['comment']);

        $this->assertEquals(0, $this->item->fresh()->comments()->count());
    }
}