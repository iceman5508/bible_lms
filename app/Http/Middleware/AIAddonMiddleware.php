<?php

namespace App\Http\Middleware;

use App\Traits\ApiStatusTrait;
use Closure;
use Illuminate\Http\Request;

class AIAddonMiddleware
{
    use ApiStatusTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->role;
        if (!(isAddonInstalled('LMSZAIAI') && in_array($role, [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION, USER_ROLE_ADMIN]))) {
            if ($request->wantsJson()) {
                $msg = __("Unauthorize route");
                return $this->error([], $msg, 403);
            } else {
                abort('403');
            }
        }

        return $next($request);
    }
}
