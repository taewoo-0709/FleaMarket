<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Condition::factory()->count(3)->create();
        Category::factory()->count(5)->create();
    }

    /** @test */
    public function product_detail_displays_all_required_information_including_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $condition = Condition::first();
        $categories = Category::factory()->count(2)->create();

        $image = UploadedFile::fake()->image('test_image.jpg');

        $item = Item::factory()->create([
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 12345,
            'item_explain' => 'テスト商品の説明',
            'condition_id' => $condition->id,
            'image_url' => $image->store('items', 'public'),
        ]);

        $item->categories()->attach($categories->pluck('id'));

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素敵な商品です',
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee(asset('storage/' . $item->image_url));
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('¥12,345');
        $response->assertSee('テスト商品の説明');
        $response->assertSee($condition->condition_kind);
        foreach ($categories as $category) {
            $response->assertSee($category->category_name);
        }
        $response->assertSee('1');
        $response->assertSee('1');
        $response->assertSee('素敵な商品です');
        $response->assertSee($user->name);
    }

    /** @test */
    public function multiple_selected_categories_are_displayed()
    {
        $categories = Category::factory()->count(3)->create();
        $item = Item::factory()->create();
        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->category_name);
        }
    }
}