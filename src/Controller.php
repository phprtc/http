<?php

namespace RTC\Http;

use JetBrains\PhpStorm\Pure;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\ResponseInterface;

abstract class Controller
{
    protected ResponseInterface $response;


    #[Pure] public function __construct(protected RequestInterface $request)
    {
        $this->response = $this->request->getResponse();
    }
}