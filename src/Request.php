<?php

namespace RTC\Http;

use QuickRoute\Router\DispatchResult;
use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use RTC\Contracts\Http\ResponseInterface;
use Swoole\Http\Request as SWRequest;
use Swoole\Http\Response as SWResponse;

class Request extends \GuzzleHttp\Psr7\Request implements RequestInterface
{
    protected ResponseInterface $RTCResponse;
    protected RequestMiddleware $middleware;


    public function __construct(
        protected SWRequest           $request,
        protected SWResponse          $response,
        protected DispatchResult|null $dispatchResult
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
     */
    public function initMiddleware(MiddlewareInterface|string ...$middlewares): void
    {
        $this->middleware = new RequestMiddleware($this, $middlewares);
        $this->middleware->getCurrent()->handle($this);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->RTCResponse;
    }

    public function getMiddleware(): RequestMiddlewareInterface
    {
        return $this->middleware;
    }

    public function getRouteDispatchResult(): DispatchResult
    {
        return $this->dispatchResult;
    }
}