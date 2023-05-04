<?php

namespace RTC\Http\Middlewares;

use HttpStatusCodes\StatusCode;
use RTC\Contracts\Http\HttpException;
use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Exceptions\MiddlewareException;
use RTC\Http\Middleware;

class RouteDispatcherMiddleware extends Middleware
{
    /**
     * @throws MiddlewareException|HttpException
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
     * @throws MiddlewareException|HttpException
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
        $request->getResponse()->html(
            code: sprintf('Page "%s" Not Found', $request->getUri()),
            status: StatusCode::NOT_FOUND
        );
    }

    /**
     * @param RequestInterface $request
     */
    protected function generateMethodNotAllowedResponse(RequestInterface $request): void
    {
        $request->getResponse()->html(
            code: 'Method Not Allowed',
            status: StatusCode::METHOD_NOT_ALLOWED
        );
    }
}