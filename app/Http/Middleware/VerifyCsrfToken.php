<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/student/pay-via-sslcommerz', '/student/success','/student/cancel','/student/fail','/student/ipn', '/payment-notify/*', '/payment-notify-subscription/*', 'payment-cancel/*'
    ];
}
