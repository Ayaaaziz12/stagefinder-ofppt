<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    // app/Http/Middleware/JwtMiddleware.php
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            // Dynamically resolve the guard (student or company)
            $user = JWTAuth::parseToken()->authenticate();
            auth()->shouldUse($guard); // Set the guard for the request
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
