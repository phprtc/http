<?php


namespace RTC\Http\Middlewares;


use RTC\Contracts\Http\RequestInterface;
use RTC\Http\Middleware;
use Throwable;

class ControllerExecutorMiddleware extends Middleware
{
    public function handle(RequestInterface $request): void
    {
        $handler = $request->getRouteDispatchResult()->getRoute()->getHandler();

        if (is_callable($handler)) {
            try {
                go(fn() => $handler($request));
            } catch (Throwable $exception) {
                handleException($exception, $request);
            }

            return;
        }

        $controller = $handler[0];
        $method = $handler[1];

        $initController = new $controller($request);

        go(function () use ($initController, $method, $request) {
            try {
                /**@phpstan-ignore-next-line* */
                call_user_func_array([$initController, $method], [$request]);
            } catch (Throwable $exception) {
                handleException($exception, $request);
            }
        });
    }
}