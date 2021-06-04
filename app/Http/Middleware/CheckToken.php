<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Token;
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
        $token = Token::where('token', $request->get('token'))->first();
        if (!$token) {
            return response()->json([
                'status' => false,
                'messages' => 'unauthorized',
            ], 401);
        }
        return $next($request);
    }
}
