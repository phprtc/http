<?php

namespace RTC\Http;

use QuickRoute\Router\Collector;
use RTC\Contracts\Http\HttpHandlerInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Middlewares\ControllerExecutorMiddleware;

class DefaultHttpHandler implements HttpHandlerInterface
{
    protected Collector $collector;


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

    public function setRouteCollector(Collector $collector): static
    {
        $this->collector = $collector;
        return $this;
    }

    public function hasRouteCollector(): bool
    {
        return isset($this->collector);
    }

    public function getRouteCollector(): Collector
    {
        return $this->collector;
    }


    /**
     * @param Request $request
     */
    protected function generateFoundResponse(Request $request): void
    {
        $request->getMiddleware()->push(new ControllerExecutorMiddleware());
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