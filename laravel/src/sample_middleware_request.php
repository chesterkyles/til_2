<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiKeyIsValid
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
        $token = $request->header('x-auth-api-token');
        if ($token !== config('auth.api_key')) {
            return response()->json(['message' => 'This token is invalid: ' . $token], 403);
        }
        return $next($request);
    }
}
