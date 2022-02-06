<?php

namespace RTC\Http;

use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * @var string[] $middlewareParams
     */
    protected array $middlewareParams = [];


    public function __construct(string $middlewareUsers = '')
    {
        if ('' !== $middlewareUsers) {
            $this->middlewareParams = explode(',', $middlewareUsers);
        }
    }

    public function handle(RequestInterface $request): void
    {
        $request->getMiddleware()->next();
    }
}