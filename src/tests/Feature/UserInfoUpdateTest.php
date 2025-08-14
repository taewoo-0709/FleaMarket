<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserInfoUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function profile_update_form_displays_existing_user_data()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'postcode' => '123-4567',
            'address' => '東京都テスト区1-2-3',
            'building' => 'テストビル3F',
            'avatar' => 'avatars/sample.png',
        ]);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertStatus(200)
            ->assertSee('value="テストユーザー"', false)
            ->assertSee('value="123-4567"', false)
            ->assertSee('value="東京都テスト区1-2-3"', false)
            ->assertSee('value="テストビル3F"', false)
            ->assertSee('src="' . asset('storage/' . $user->avatar) . '"', false);
    }
}