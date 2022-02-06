<?php

namespace RTC\Http;

use QuickRoute\Router\Collector;
use RTC\Contracts\Http\HttpHandlerInterface;
use RTC\Contracts\Http\RequestInterface;

class DefaultHttpHandler implements HttpHandlerInterface
{
    protected Collector $collector;


    public function handle(RequestInterface $request): void
    {
        $request->getResponse()->html('Hello World, PHPRTC Alive!');
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
}