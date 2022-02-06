<?php


namespace RTC\Http;


use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use SplQueue;

class RequestMiddleware implements RequestMiddlewareInterface
{
    protected SplQueue $queue;

    public function __construct(protected RequestInterface $request, array $middlewares)
    {
        $this->queue = new SplQueue();

        foreach ($middlewares as $middleware) {
            $this->queue->push(is_object($middleware) ? $middleware : new $middleware);
        }

        $this->queue->rewind();
    }

    public function push(MiddlewareInterface $middleware): void
    {
        $this->queue->push($middleware);
    }

    public function next(): void
    {
        $this->queue->next();
        $this->queue->current()->handle($this->request);
    }

    public function getCurrent(): MiddlewareInterface
    {
        return $this->queue->current();
    }

    public function getQueue(): SplQueue
    {
        return $this->queue;
    }
}