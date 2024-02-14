<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Organization
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * role 2 instructor
         * only instructor can access instructor panel
         */

        if (file_exists(storage_path('installed'))) {
            if (auth()->user()->role == USER_ROLE_ORGANIZATION && auth()->user()->organization->status == STATUS_APPROVED) {
                return $next($request);
            } else {
                abort('403');
            }
        } else {
            return redirect()->to('/install');
        }
    }
}
