<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\CodeVerifyNotification;
use Illuminate\Support\Facades\Notification;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_triggers_email_verification()
    {
        Notification::fake();

        // ゲストとしてユーザー登録（actingAsは不要）
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, CodeVerifyNotification::class);
    }

    /** @test */
    public function user_can_verify_email_with_correct_code()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $code = 1234;

        session([
            'verification_code' => $code,
            'registered_user' => $user->id
        ]);

        $this->post('/verify-code', [
            'code' => str_split($code)
        ])->assertRedirect(route('items.list'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function user_cannot_verify_email_with_wrong_code()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        session([
            'verification_code' => 1234,
            'registered_user' => $user->id
        ]);

        $this->post('/verify-code', [
            'code' => [0, 0, 0, 0]
        ])->assertSessionHas('code_error', '認証コードが違います');

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function unverified_user_cannot_login_and_redirects_to_code_form()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])
        ->assertRedirect(route('verify_code')); // 認証コード入力フォームにリダイレクト
    }

    /** @test */
    public function verified_user_can_login_successfully()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])
        ->assertRedirect(route('items.list'));
    }
}
