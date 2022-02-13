<?php


namespace RTC\Http\Middlewares;


use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Exceptions\MiddlewareException;
use RTC\Http\Kernel;
use RTC\Http\Middleware;

class RouteMiddlewareExecutorMiddleware extends Middleware
{
    /**
     * @throws MiddlewareException
     */
    public function handle(RequestInterface $request): void
    {
        $middlewares = $request->getRouteDispatchResult()->getRoute()->getMiddleware();

        foreach ($middlewares as $middlewareName) {
            /**
             * Handles middleware with params
             * Example(auth:admin,user)
             * @var string $middlewareName
             */
            $middlewareName = explode(':', $middlewareName);
            $middlewareClass = Kernel::createSingleton()->getHttpRouteMiddlewares()[$middlewareName[0]] ?? null;

            if (null === $middlewareClass) {
                MiddlewareException::throw($request, "No middleware with name \"$middlewareName\" is defined.");
            }

            $request->getMiddleware()->push(new $middlewareClass($middlewareName[1] ?? ''));
        }

        // Append controller executor middleware
        $request->getMiddleware()->push(new ControllerExecutorMiddleware());
        $request->getMiddleware()->next();
    }
}