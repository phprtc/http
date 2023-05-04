<?php

declare(strict_types=1);

namespace RTC\Http;

use HttpStatusCodes\StatusCode;
use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\ResponseInterface;
use RTC\Http\Exceptions\HtmlFileNotFoundException;
use Swoole\Http\Response as Http1Response;

class Response implements ResponseInterface
{
    public function __construct(
        protected RequestInterface $request,
        protected Http1Response    $response
    )
    {
    }

    public function json(object|array $data, StatusCode $status = StatusCode::OK, array $headers = []): void
    {
        $this->plain(
            string: strval(json_encode($data)),
            status: $status,
            headers: array_merge(['Content-Type' => 'application/json'], $headers)
        );
    }

    public function plain(string $string, StatusCode $status = StatusCode::OK, array $headers = []): void
    {
        if ($this->response->isWritable()) {
            foreach ($headers as $key => $value) {
                $this->response->setHeader($key, $value);
            }

            $this->response->setHeader('Server', 'PHP_RTC');
            $this->response->setStatusCode($status->value);
            $this->response->end($string);
        }
    }

    public function html(string $code, StatusCode $status = StatusCode::OK, array $headers = []): void
    {
        $this->plain(
            $code,
            $status,
            array_merge(['Content-Type' => 'text/html'], $headers)
        );
    }

    /**
     * Reload webpage
     */
    public function reload(): void
    {
        $this->redirect($this->request->getUri()->getPath());
    }

    /**
     * Redirect to another webpage
     *
     * @param string $url
     * @param array $headers
     */
    public function redirect(string $url, array $headers = []): void
    {
        $this->response->setHeader('Location', $url);

        foreach ($headers as $name => $header) {
            $this->response->setHeader($name, $header);
        }

        $this->plain('', StatusCode::FOUND);
    }

    public function header(string $name, string $value): static
    {
        $this->response->setHeader($name, $value);
        return $this;
    }

    public function sendFile(string $path, int $offset = 0, int $length = 0): void
    {
        $this->response->sendfile($path, $offset, $length);
    }

    public function serveHtmlFile(string $path): void
    {
        if (!file_exists($path)) {
            throw new HtmlFileNotFoundException(
                request: $this->request,
                message: "Html file '$path' does not exists"
            );
        }

        $this->html(strval(file_get_contents($path)));
    }

    public function trailer(string $key, string $value): void
    {
        $this->response->trailer($key, $value);
    }

    public function write(string $data): static
    {
        $this->response->write($data);
        return $this;
    }

    public function cookie(
        string $key,
        string $value = '',
        int    $expire = 0,
        string $path = '/',
        string $domain = '',
        bool   $secure = false,
        bool   $httponly = false,
        string $samesite = '',
        string $priority = ''
    ): static
    {
        $this->response->cookie(
            $key,
            $value,
            $expire,
            $path,
            $domain,
            $secure,
            $httponly,
            $samesite,
            $priority
        );

        return $this;
    }
}