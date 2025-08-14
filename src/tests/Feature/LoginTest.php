<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_is_required()
    {
        $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ])
        ->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /** @test */
    public function password_is_required()
    {
        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ])
        ->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /** @test */
    public function invalid_credentials()
    {
        $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpassword',
        ])
        ->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /** @test */
    public function successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ])
        ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }
}