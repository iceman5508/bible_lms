<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AIModuleMiddleware
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
        $codeBuildVersion = getAddonCodeBuildVersion('LMSZAIAI');
        $dbBuildVersion = getCustomerAddonBuildVersion('LMSZAIAI');
        if ($codeBuildVersion > $dbBuildVersion) {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            return redirect()->route('admin.addon.details', 'LMSZAIAI');
        }
        return $next($request);
    }
}
