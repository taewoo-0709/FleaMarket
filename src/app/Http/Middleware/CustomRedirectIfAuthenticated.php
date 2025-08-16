<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomRedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            $user = Auth::guard($guard)->user();

            if ($user) {
                if (!$user->hasVerifiedEmail()) {
                    return $next($request);
                }
                return redirect()->intended(config('fortify.home'));
            }
        }
        return $next($request);
    }
}
