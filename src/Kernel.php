<?php

namespace RTC\Http;

use RTC\Contracts\Http\KernelInterface;
use RTC\Http\Middlewares\RouteDispatcherMiddleware;
use RTC\Http\Middlewares\RouteMiddlewareExecutorMiddleware;
use RTC\Utils\InstanceCreator;

class Kernel implements KernelInterface
{

    use InstanceCreator;

    protected array $middlewares = [];

    protected array $defaultMiddlewares = [
        RouteDispatcherMiddleware::class,
        RouteMiddlewareExecutorMiddleware::class,
    ];

    /**
     * Specifies whether to use default http middlewares
     *
     * @var bool $useDefaultMiddlewares
     */
    protected bool $useDefaultMiddlewares = true;

    protected array $routeMiddlewares = [];

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @return array
     */
    public function getDefaultMiddlewares(): array
    {
        return $this->useDefaultMiddlewares
            ? array_merge($this->defaultMiddlewares, $this->middlewares)
            : $this->middlewares;
    }

    public function getRouteMiddlewares(): array
    {
        return $this->routeMiddlewares;
    }

    /**
     * @inheritDoc
     */
    public function shouldUseDefaultMiddlewares(): bool
    {
        return $this->useDefaultMiddlewares;
    }
}