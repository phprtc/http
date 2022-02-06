<?php


namespace RTC\Http\View;

use RTC\Http\OldRequest;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractView
{
    /**
     * @param OldRequest $request
     * @param string $viewFile
     * @param array $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    abstract public function renderFile(OldRequest $request, string $viewFile, array $data = []): string;
}