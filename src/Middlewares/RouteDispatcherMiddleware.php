<?php

namespace RTC\Http\Middlewares;

use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Exceptions\MiddlewareException;
use RTC\Http\Middleware;
use RTC\Http\Request;

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
     * @param Request $request
     * @throws MiddlewareException
     */
    protected function generateFoundResponse(Request $request): void
    {
        $request->getMiddleware()->next();
    }

    /**
     * @param Request $request
     */
    protected function generateNotFoundResponse(Request $request): void
    {
        $request->getResponse()->html('Page Not Found', 404);
    }

    /**
     * @param Request $request
     */
    protected function generateMethodNotAllowedResponse(Request $request): void
    {
        $request->getResponse()->html('Method Not Allowed', 405);
    }
}