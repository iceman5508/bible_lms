<?php

namespace App\Http\Middleware;

use App\Traits\General;
use Closure;
use Illuminate\Http\Request;

class IsDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    use General;
    public function handle(Request $request, Closure $next)
    {
        if(env('APP_DEMO') == 'active'){
            if($request->route()->getName() == 'update-language'){
                $response['msg'] = __("This is a demo version! You can get full access after purchasing the application.");
                $response['status'] = 404;
                return response()->json($response);
            }
            else{
                $this->showToastrMessage('error', __('This is a demo version! You can get full access after purchasing the application.'));
                return redirect()->back();
            }

        }

        return $next($request);
    }
}
