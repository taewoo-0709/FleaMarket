<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
