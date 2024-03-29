<?php

namespace RTC\Http;

use RTC\Contracts\Exceptions\RuntimeException;
use RTC\Contracts\Http\KernelInterface;
use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use RTC\Contracts\Http\ResponseInterface;
use RTC\Contracts\Http\Router\CollectorInterface;
use RTC\Contracts\Http\Router\DispatchResultInterface;
use RTC\Contracts\Server\ServerInterface;
use Swoole\Http\Request as Http1Request;
use Swoole\Http\Response as Http1Response;
use Throwable;

class Request extends \GuzzleHttp\Psr7\Request implements RequestInterface
{
    protected ResponseInterface $RTCResponse;
    protected RequestMiddleware $middleware;


    public function __construct(
        protected Http1Request                 $request,
        protected Http1Response                $response,
        protected KernelInterface              $kernel,
        protected DispatchResultInterface|null $dispatchResult,
        private readonly ServerInterface       $server,
    )
    {
        $this->RTCResponse = new Response($this, $this->response);

        parent::__construct(
            $request->getMethod(),
            $request->server['request_uri'],
            $request->header,
            strval($request->getContent())
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
    }

    public function handleException(Throwable $exception): void
    {
        $this->getResponse()->html((string)$exception);
    }

    public function hasRouteCollector(): bool
    {
        return isset($this->dispatchResult);
    }

    public function getRouteCollector(): CollectorInterface
    {
        return $this->getRouteDispatchResult()->getCollector();
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
        if (!isset($this->dispatchResult)) {
            throw new RuntimeException('Route has not been dispatched');
        }

        return $this->dispatchResult;
    }

    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    public function getServer(): ServerInterface
    {
        return $this->server;
    }
}