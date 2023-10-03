<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateCustom
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
        if (!$request->user() && !$request->expectsJson()) {
            return response()->json(['status_code' => '401', 'message' => 'You are not authenticated.'], 401);
        }

        return $next($request);
    }
}
