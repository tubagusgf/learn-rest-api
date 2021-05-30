<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->get('token')) {
            return response()->json([
                'status' => false,
                'messages' => 'unauthorized',
            ], 401);
        }
        return $next($request);
    }
}
