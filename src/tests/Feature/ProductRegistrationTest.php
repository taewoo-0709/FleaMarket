<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_item_with_all_required_fields()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $condition = Condition::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('item.jpg');

        $itemData = [
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'item_explain' => '商品の説明です',
            'price' => 5000,
            'condition_id' => $condition->id,
            'category_id' => $categories->pluck('id')->toArray(),
            'image_url' => $file,
        ];

        $this->post(route('items.store'), $itemData)->assertStatus(302);

        $item = Item::first();

        $this->assertDatabaseHas('items', [
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'item_explain' => '商品の説明です',
            'price' => 5000,
            'condition_id' => $condition->id,
        ]);

        Storage::disk('public')->assertExists($item->image_url);

        foreach ($categories as $category) {
            $this->assertTrue($item->categories->contains($category));
        }
    }
}