<?php

namespace RTC\Http\Exceptions;

use RTC\Contracts\Http\RequestInterface;
use Throwable;

class MiddlewareException extends HttpException
{
    /**
     * @param RequestInterface $request
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @return never
     * @throws MiddlewareException|HttpException
     */
    public static function throw(RequestInterface $request, string $message, int $code = 0, ?Throwable $previous = null): never
    {
        parent::throw($request, $message, $code, $previous);
    }
}