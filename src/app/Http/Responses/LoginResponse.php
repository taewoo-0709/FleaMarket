<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user && is_null($user->email_verified_at)) {

            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice');
        }

        return redirect()->intended(config('fortify.home', '/'));
    }
}
