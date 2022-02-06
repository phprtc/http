<?php

namespace RTC\Http\Router;

use QuickRoute\Router\DispatchResult;

abstract class AbstractDispatcher
{
    abstract public function dispatch(string $method, string $path): DispatchResult;
}