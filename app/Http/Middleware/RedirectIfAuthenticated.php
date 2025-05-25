<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['error' => 'Already authenticated.'], 200);
                }
                return redirect('/home');
            }
        }

        return $next($request);
    }
}
