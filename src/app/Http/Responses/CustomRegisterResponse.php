<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class CustomRegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        if ($request->user()) {
            session(['registered_user_id' => $request->user()->id]);
        }

        Auth::logout();

        return redirect()->route('verification.notice');
    }
}
