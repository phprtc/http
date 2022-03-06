<?php


namespace RTC\Http;


use JetBrains\PhpStorm\Pure;
use RTC\Contracts\Http\MiddlewareInterface;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\RequestMiddlewareInterface;
use RTC\Http\Exceptions\MiddlewareException;

class RequestMiddleware implements RequestMiddlewareInterface
{
    protected array $middlewares = [];
    protected int $middlewareIndex = 0;
    protected null|MiddlewareInterface $nextMiddleware;
    protected bool $hasStarted = false;

    public function __construct(protected RequestInterface $request, array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = is_object($middleware) ? $middleware : new $middleware;
        }
    }

    public function push(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return void
     * @throws MiddlewareException
     */
    public function next(): void
    {
        if (!$this->hasStarted) {
            $this->nextMiddleware = $this->middlewares[$this->middlewareIndex];
        }

        if (!isset($this->nextMiddleware)) {
            MiddlewareException::throw($this->request, 'There is no next middleware');
        }

        $nextMiddleware = clone $this->nextMiddleware;

        $this->middlewareIndex += 1;
        $this->nextMiddleware = $this->middlewares[$this->middlewareIndex] ?? null;

        $nextMiddleware->handle($this->request);
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
        $index = 0 == $this->middlewareIndex ? 0 : $this->middlewareIndex - 1;
        return $this->middlewares[$index] ?? null;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}