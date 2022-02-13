<?php


namespace RTC\Http;


use JetBrains\PhpStorm\Pure;
use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use RTC\Http\Exceptions\MiddlewareException;
use SplQueue;

class RequestMiddleware implements RequestMiddlewareInterface
{
    protected SplQueue $queue;
    protected null|MiddlewareInterface $nextMiddleware;
    protected bool $hasStarted = false;

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

    /**
     * @return void
     * @throws MiddlewareException
     */
    public function next(): void
    {
        if (!$this->hasStarted) {
            $this->nextMiddleware = $this->queue->current();
        }

        if (!isset($this->nextMiddleware)) {
            MiddlewareException::throw($this->request, 'There is no next middleware');
        }

        $next = clone $this->nextMiddleware;

        $this->queue->next();
        $this->nextMiddleware = $this->queue->current();

        $next->handle($this->request);
    }

    public function getNext(): null|MiddlewareInterface
    {
        return $this->nextMiddleware;
    }

    #[Pure] public function hasNext(): bool
    {
        return isset($this->nextMiddleware);
    }

    public function getCurrent(): null|MiddlewareInterface
    {
        return $this->queue->current();
    }

    public function getQueue(): SplQueue
    {
        return $this->queue;
    }
}