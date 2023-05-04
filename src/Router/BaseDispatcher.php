<?php

namespace RTC\Http\Router;

use RTC\Contracts\Http\Router\DispatchResultInterface;

class BaseDispatcher extends AbstractDispatcher
{
    public function dispatch(string $method, string $path): DispatchResultInterface
    {
        $collector = Collector::create();

//        foreach (HttpConfig::getSettings()['route_files'] as $route_file) {
//            $collector->collectFile($route_file);
//        }

        return Dispatcher::create($collector)->dispatch($method, $path);
    }
}