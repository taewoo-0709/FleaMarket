<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Registered;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_triggers_email_verification()
    {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        event(new Registered($user));

        Notification::assertSentTo([$user], CustomVerifyEmail::class);
    }

    /** @test */
    public function email_verification_notice_page_shows_button()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertStatus(200)
            ->assertSee('認証はこちらから')
            ->assertSee('認証メールを再送する');
    }

    /** @test */
    public function user_can_verify_email_and_is_redirected_to_home()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect('/');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function verified_user_can_access_home_with_verified_middleware()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200);
    }

    /** @test */
    public function unverified_user_is_redirected_to_verification_notice()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('verification.notice'));
    }
}