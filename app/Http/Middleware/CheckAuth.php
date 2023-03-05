<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $token = $request->bearerToken();
        $user = DB::table('users')->where('api_token', '=', $token)->first();

        if (empty($token) || !$user || $user->role != $role) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized!'
            ], 401);
        }
        return $next($request);
    }
}
