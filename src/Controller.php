<?php

namespace RTC\Http;

use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\ResponseInterface;

abstract class Controller
{
    protected ResponseInterface $response;
    protected RequestInterface $request;


    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->response = $this->request->getResponse();
    }
}