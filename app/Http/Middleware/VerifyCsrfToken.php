<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*users-inactive',
        '*users-active',
        'admin/service',
        '*services/active',
        '*services/inactive',
        '*topUp/active',
        '*topUp/inactive',

        '*voucher/active',
        '*voucher/inactive',

        '*voucher/service/code/active',
        '*voucher/service/code/inactive',
        '*voucher/service/codes/delete',

        '*gift-card/active',
        '*gift-card/inactive',

        '*gift-card/service/code/active',
        '*gift-card/service/code/inactive',
        '*gift-card/service/codes/delete',

        '*sell-post/active',
        '*sell-post/inactive',

        '*post/sell/hold',
        '*post/sell/release',

        '*sort-payment-methods',
        '*add-fund',
        'success',
        'failed',
        'payment/*',
        '*ajax*',
        '*push-chat*',
    ];
}
