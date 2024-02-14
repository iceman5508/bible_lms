<?php

namespace App\Http\Middleware;

use App\Traits\ApiStatusTrait;
use Closure;
use Illuminate\Http\Request;

class Instrucotr
{
    use ApiStatusTrait;
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
            if (auth()->user()->role == 2 && auth()->user()->instructor->status == 1) {
                return $next($request);
            } else {
                if ($request->wantsJson()) {
                    $msg = __("Unauthorize route");
                    return $this->error([], $msg, 403);
                } else {
                    abort('403');
                }
            }
        } else {
            return redirect()->to('/install');
        }
    }
}
