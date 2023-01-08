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
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $checkExist = DB::table('users')->where('api_token', '=', $token)->first();

        if (empty($token) || !$checkExist) {
            return response('Unauthorized!', 401);
        }
        return $next($request);
    }
}
