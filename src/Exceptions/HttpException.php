<?php

namespace RTC\Http\Exceptions;

use JetBrains\PhpStorm\Pure;
use RTC\Contracts\Http\RequestInterface;
use Throwable;

class HttpException extends \RTC\Contracts\Http\HttpException
{

    /**
     * @param RequestInterface $request
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @return never
     * @throws HttpException
     */
    public static function throw(RequestInterface $request, string $message, int $code = 0, ?Throwable $previous = null): never
    {
        throw new static($request, $message, $code, $previous);
    }

    #[Pure] public function __construct(
        protected RequestInterface $request,
        string                     $message = "",
        int                        $code = 0,
        ?Throwable                 $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}