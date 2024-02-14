<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PrivateModeMiddleware
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
        if(!get_option('private_mode') || !auth()->guest()){
            return $next($request);
        }
        else{
            return redirect()->route('login');
        }
    }
}
