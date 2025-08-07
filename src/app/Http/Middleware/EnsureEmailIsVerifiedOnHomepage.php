<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerifiedOnHomepage
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->hasVerifiedEmail()) {
            return $next($request);
        }

        if ($request->is('/')) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
