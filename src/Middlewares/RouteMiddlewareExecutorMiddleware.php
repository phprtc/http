<?php


namespace RTC\Http\Middlewares;


use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Exceptions\MiddlewareException;
use RTC\Http\Middleware;

class RouteMiddlewareExecutorMiddleware extends Middleware
{
    /**
     * @throws MiddlewareException
     */
    public function handle(RequestInterface $request): void
    {
        $middlewares = $request->getRouteDispatchResult()->getRoute()->getMiddleware();

        foreach ($middlewares as $routeMiddleware) {
            /**
             * Handles middleware with params
             * Example(auth:admin,user)
             * @var string $routeMiddleware
             */
            $routeMiddleware = explode(':', $routeMiddleware);

            $kernelHttpRouteMiddlewares = $request->getKernel()->getRouteMiddlewares()[$routeMiddleware[0]] ?? null;

            if (null === $kernelHttpRouteMiddlewares) {
                MiddlewareException::throw($request, "No middleware with name \"$routeMiddleware\" is defined.");
            }

            /**@phpstan-ignore-next-line**/
            $request->getMiddleware()->push(new $kernelHttpRouteMiddlewares($routeMiddleware[1] ?? ''));
        }

        // Append controller executor middleware
        $request->getMiddleware()->push(new ControllerExecutorMiddleware());
        $request->getMiddleware()->next();
    }
}