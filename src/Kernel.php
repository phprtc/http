<?php

namespace RTC\Http;

use RTC\Http\Middlewares\ControllerExecutorMiddleware;
use RTC\Http\Middlewares\RouteDispatcherMiddleware;
use RTC\Http\Middlewares\RouteMiddlewareExecutorMiddleware;

abstract class Kernel
{
    protected static array $httpMiddlewares = [
        RouteDispatcherMiddleware::class,
        RouteMiddlewareExecutorMiddleware::class,
    ];

    protected static array $routeMiddlewares = [];

    /**
     * @return array
     */
    public static function getHttpMiddlewares(): array
    {
        return self::$httpMiddlewares;
    }

    /**
     * @return array
     */
    public static function getRouteMiddlewares(): array
    {
        return self::$routeMiddlewares;
    }
}