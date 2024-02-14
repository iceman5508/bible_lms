<?php

namespace App\Http;

use App\Http\Middleware\Admin;
use App\Http\Middleware\Affiliator;
use App\Http\Middleware\AddonMiddleware;
use App\Http\Middleware\AIAddonMiddleware;
use App\Http\Middleware\Common;
use App\Http\Middleware\CourseAccessMiddleware;
use App\Http\Middleware\DeviceControlMiddleware;
use App\Http\Middleware\Instrucotr;
use App\Http\Middleware\IsDemo;
use App\Http\Middleware\LocalizationMiddleware;
use App\Http\Middleware\PrivateModeMiddleware;
use App\Http\Middleware\SaasCheck;
use App\Http\Middleware\Organization;
use App\Http\Middleware\Student;
use App\Http\Middleware\VersionUpdate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Spatie\CookieConsent\CookieConsentMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'local' =>  LocalizationMiddleware::class,
        'common' => Common::class,
        'student' => Student::class,
        'instructor' => Instrucotr::class,
        'organization' => Organization::class,
        'affiliate' => Affiliator::class,
        'admin' => Admin::class,
        'isDemo' => IsDemo::class,
        'version.update' => VersionUpdate::class,
        'course.access' => CourseAccessMiddleware::class,
        'device.control' => DeviceControlMiddleware::class,
        'private.mode' => PrivateModeMiddleware::class,
        'saas_check' => SaasCheck::class,
        'ai-access' => AIAddonMiddleware::class,
        'addon' => AddonMiddleware::class,
    ];
}
