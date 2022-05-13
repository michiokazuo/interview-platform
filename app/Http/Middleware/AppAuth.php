<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AppAuth
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
        logger()->info('User from token:' . $userFromToken);
        if (empty($userFromToken)) {
            return response()->json([
                'errors' => [
                    'message' => 'Session expired. Please log in again.'
                ]
            ], 401);
        }
        return $next($request);
    }
}
