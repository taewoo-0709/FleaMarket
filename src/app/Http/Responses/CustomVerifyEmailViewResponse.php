<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse;

class CustomVerifyEmailViewResponse implements VerifyEmailViewResponse
{
    public function toResponse($request)
    {
        return view('auth.verify');
    }
}
