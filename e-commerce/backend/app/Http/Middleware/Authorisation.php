<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authorisation
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
    
        if ($user && $user->isAdmin) {
            return $next($request);
        }

        return response()->json(['message' => 'You have no permission'], Response::HTTP_FORBIDDEN);
    }
}
