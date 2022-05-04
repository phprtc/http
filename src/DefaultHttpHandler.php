<?php

namespace RTC\Http;

use RTC\Contracts\Http\HttpHandlerInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\Router\CollectorInterface;

class DefaultHttpHandler implements HttpHandlerInterface
{
    protected CollectorInterface $collector;

    public function onReady(): void
    {
    }

    public function handle(RequestInterface $request): void
    {
        $request->getResponse()->html('Hello World, PHPRTC Alive!');
    }

    public function setRouteCollector(CollectorInterface $collector): static
    {
        $this->collector = $collector;
        return $this;
    }

    public function hasRouteCollector(): bool
    {
        return isset($this->collector);
    }

    public function getRouteCollector(): CollectorInterface
    {
        return $this->collector;
    }
}