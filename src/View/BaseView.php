<?php

namespace RTC\Http\View;

use RTC\Http\OldRequest;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class BaseView extends AbstractView
{
    protected Environment $environment;


    public function __construct()
    {
        $this->environment = new Environment(new FilesystemLoader(view_path()));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public static function render(OldRequest $request, string $viewFile, array $data = []): string
    {
        return (new static())->renderFile($request, $viewFile, $data);
    }

    public function renderFile(OldRequest $request, string $viewFile, array $data = []): string
    {
        if (!strpos($viewFile, '.twig')) {
            $viewFile .= '.twig';
        }

        return $this->environment->render($viewFile, [
            'page' => $data,
            'request' => $request,
        ]);
    }
}