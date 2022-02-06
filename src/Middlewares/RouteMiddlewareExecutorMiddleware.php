<?php


namespace RTC\Http\Middlewares;


use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Kernel;
use RTC\Http\Middleware;
use Swoole\Exception;

class RouteMiddlewareExecutorMiddleware extends Middleware
{
    /**
     * @throws Exception
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
            $middlewareClass = Kernel::getRouteMiddlewares()[$middlewareName[0]] ?? null;

            if (null === $middlewareClass) {
                throw new Exception("No middleware with name \"$middlewareName\" is defined.");
            }

            $request->getMiddleware()->push(new $middlewareClass($middlewareName[1] ?? ''));
        }

        // Append controller executor middleware
        $request->getMiddleware()->push(new ControllerExecutorMiddleware());
        $request->getMiddleware()->next();
    }
}