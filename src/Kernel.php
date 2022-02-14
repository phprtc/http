<?php

namespace RTC\Http;

use RTC\Contracts\Http\KernelInterface;
use RTC\Http\Middlewares\RouteDispatcherMiddleware;
use RTC\Http\Middlewares\RouteMiddlewareExecutorMiddleware;
use RTC\Utils\InstanceCreator;

class Kernel implements KernelInterface
{

    use InstanceCreator;

    protected array $httpMiddlewares = [];

    protected array $httpDefaultMiddlewares = [
        RouteDispatcherMiddleware::class,
        RouteMiddlewareExecutorMiddleware::class,
    ];

    /**
     * Specifies whether to use default http middlewares
     *
     * @var bool $useDefaultHttpMiddlewares
     */
    protected bool $useDefaultHttpMiddlewares = true;

    protected array $routeMiddlewares = [];

    /**
     * @return array
     */
    public function getHttpMiddlewares(): array
    {
        return $this->httpMiddlewares;
    }

    /**
     * @return array
     */
    public function getHttpDefaultMiddlewares(): array
    {
        return $this->useDefaultHttpMiddlewares
            ? array_merge($this->httpDefaultMiddlewares, $this->httpMiddlewares)
            : $this->httpMiddlewares;
    }

    /**
     * @return array
     */
    public function getHttpRouteMiddlewares(): array
    {
        return $this->routeMiddlewares;
    }
}