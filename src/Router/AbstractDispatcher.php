<?php

namespace RTC\Http\Router;

use RTC\Contracts\Http\Router\DispatchResultInterface;

abstract class AbstractDispatcher
{
    abstract public function dispatch(string $method, string $path): DispatchResultInterface;
}