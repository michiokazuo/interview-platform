<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JWTApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userFromToken = auth('api')->user();
        if (empty($userFromToken)){
            return response()->json([
                'errors' => [
                    'message' => 'Session jwt expired. Please log in again through Shopify.'
                ]
            ], 401);
        }
        return $next($request);
    }
}
