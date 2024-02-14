<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'local'])
                ->group(base_path('routes/web.php'));


            Route::middleware(['web',  'local','version.update', 'addon'])
                ->namespace($this->namespace. '\Frontend')
                ->group(base_path('routes/frontend.php'));

            Route::middleware(['web', 'auth', 'admin', 'local','version.update', 'addon'])
                ->prefix('admin')
                ->namespace($this->namespace. '\Admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth', 'instructor', 'local','version.update', 'addon'])
                ->prefix('instructor')
                ->namespace($this->namespace. '\Instructor')
                ->group(base_path('routes/instructor.php'));

            Route::middleware(['web', 'auth', 'organization', 'local','version.update', 'addon'])
                ->prefix('organization')
                ->as('organization.')
                ->namespace($this->namespace. '\Organization')
                ->group(base_path('routes/organization.php'));

            Route::middleware(['web', 'auth', 'student', 'local','version.update', 'addon', 'device.control'])
                ->prefix('student')
                ->namespace($this->namespace. '\Student')
                ->group(base_path('routes/student.php'));


            Route::middleware(['web', 'auth', 'common', 'local','version.update', 'addon'])
                ->prefix('common')
                ->namespace($this->namespace. '\Common')
                ->group(base_path('routes/common.php'));
            
            if(isAddonInstalled('LMSZAIAI')){
                Route::middleware(['web', 'auth', 'local', 'version.update', 'addon', 'ai-access'])
                    ->prefix('ai')
                    ->namespace($this->namespace. '\AI')
                    ->group(base_path('routes/addon/ai.php'));
            }
            
            if(isAddonInstalled('LMSZAIPRODUCT')){
                Route::middleware(['web', 'local', 'version.update', 'addon'])
                    ->namespace($this->namespace. '\Product')
                    ->group(base_path('routes/addon/product.php'));
            }

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
