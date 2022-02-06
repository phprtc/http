<?php

namespace RTC\Http\Router;

use QuickRoute\Router\Collector;
use QuickRoute\Router\Dispatcher;
use QuickRoute\Router\DispatchResult;
use RTC\Http\HttpConfig;

class BaseDispatcher extends AbstractDispatcher
{
    public function dispatch(string $method, string $path): DispatchResult
    {
        $collector = Collector::create();

        foreach (HttpConfig::getSettings()['route_files'] as $route_file) {
            $collector->collectFile($route_file);
        }

        return Dispatcher::create($collector)->dispatch($method, $path);
    }
}