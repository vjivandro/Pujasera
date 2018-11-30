<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionChecker
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
        if(Auth::check())
            if(Auth::user()->role == $role)
                return $next($request);

        if($request->wantsJson())
            return response()->json([
                'message' => 'Not enough privilege to do this action',
                'errors' => [
                ]
            ], 403);
        else
            return redirect()->route('login');
    }
}
