<?php

namespace RTC\Http\Middlewares;

use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Exceptions\MiddlewareException;
use RTC\Http\Middleware;

class RouteDispatcherMiddleware extends Middleware
{
    /**
     * @throws MiddlewareException
     */
    public function handle(RequestInterface $request): void
    {
        switch (true) {
            case $request->getRouteDispatchResult()->isFound():
                $this->generateFoundResponse($request);
                break;
            case $request->getRouteDispatchResult()->isNotFound():
                $this->generateNotFoundResponse($request);
                break;
            default:
                $this->generateMethodNotAllowedResponse($request);
        }
    }

    /**
     * @param RequestInterface $request
     * @throws MiddlewareException
     */
    protected function generateFoundResponse(RequestInterface $request): void
    {
        $request->getMiddleware()->next();
    }

    /**
     * @param RequestInterface $request
     */
    protected function generateNotFoundResponse(RequestInterface $request): void
    {
        $request->getResponse()->html('Page Not Found', 404);
    }

    /**
     * @param RequestInterface $request
     */
    protected function generateMethodNotAllowedResponse(RequestInterface $request): void
    {
        $request->getResponse()->html('Method Not Allowed', 405);
    }
}