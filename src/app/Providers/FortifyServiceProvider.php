<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\CustomRegisterResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;


class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        $this->app->singleton(LoginResponse::class,
        LoginResponse::class);

        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);

        }
    }