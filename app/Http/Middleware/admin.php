<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        // dd(Auth::guard('admin-api')->user());
        if (! Auth::guard('admin-api')->user() || ! Auth::guard('admin-api')->user()->token()) {
            throw new AuthenticationException();
        }
        if ( Auth::guard('admin-api')->check() && Auth::guard('admin-api')->user()->token()->scopes[0] == "admin") {
            // dd("admin");
            return $next($request);
        } 
        return response()->json(['message' => 'Forbidden!'], 403);
        
        
    }
}
