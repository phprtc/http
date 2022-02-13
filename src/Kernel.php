<?php

namespace RTC\Http;

use RTC\Http\Middlewares\RouteDispatcherMiddleware;
use RTC\Http\Middlewares\RouteMiddlewareExecutorMiddleware;

class Kernel extends \RTC\Server\Kernel
{
    protected array $httpDefaultMiddlewares = [
        RouteDispatcherMiddleware::class,
        RouteMiddlewareExecutorMiddleware::class,
    ];
}