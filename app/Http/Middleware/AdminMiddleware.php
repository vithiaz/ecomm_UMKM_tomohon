<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->user_type == '1') {
            return abort(403);
        }
        return $next($request);
    }
}
