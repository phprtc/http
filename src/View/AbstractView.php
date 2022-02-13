<?php


namespace RTC\Http\View;

use RTC\Contracts\Http\RequestInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractView
{
    /**
     * @param RequestInterface $request
     * @param string $viewFile
     * @param array $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    abstract public function renderFile(RequestInterface $request, string $viewFile, array $data = []): string;
}