<?php

use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\CashierMiddleware;
use App\Http\Middleware\GovernmentMiddleware;
use App\Http\Middleware\RiderMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Owner' => OwnerMiddleware::class,
            'Seller' => SellerMiddleware::class,
            'Cashier' => CashierMiddleware::class,
            'GovernmentAgency' => GovernmentMiddleware::class,
            'DeliveryRider' => RiderMiddleware::class,
            'Customer' => CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
