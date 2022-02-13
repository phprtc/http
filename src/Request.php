<?php

namespace RTC\Http;

use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use RTC\Contracts\Http\ResponseInterface;
use RTC\Contracts\Http\Router\DispatchResultInterface;
use Swoole\Http\Request as Http1Request;
use Swoole\Http\Response as Http1Response;
use Swoole\Http2\Request as Http2Request;
use Swoole\Http2\Response as Http2Response;
use Throwable;

class Request extends \GuzzleHttp\Psr7\Request implements RequestInterface
{
    protected ResponseInterface $RTCResponse;
    protected RequestMiddleware $middleware;


    public function __construct(
        protected Http1Request|Http2Request    $request,
        protected Http1Response|Http2Response  $response,
        protected DispatchResultInterface|null $dispatchResult
    )
    {
        $this->RTCResponse = new Response($this, $this->response);

        parent::__construct(
            $request->getMethod(),
            $request->server['request_uri'],
            $request->header,
            $request->getContent()
        );
    }

    public function init(string $name, mixed $value): Request
    {
        $this->$name = $value;
        return $this;
    }

    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * @param MiddlewareInterface|string ...$middlewares
     * @throws Exceptions\MiddlewareException
     */
    public function initMiddleware(MiddlewareInterface|string ...$middlewares): void
    {
        $this->middleware = new RequestMiddleware($this, $middlewares);
        $this->middleware->next();
    }

    public function handleException(Throwable $exception): void
    {
        $this->getResponse()->html((string)$exception);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->RTCResponse;
    }

    public function getMiddleware(): RequestMiddlewareInterface
    {
        return $this->middleware;
    }

    public function getRouteDispatchResult(): DispatchResultInterface
    {
        return $this->dispatchResult;
    }
}