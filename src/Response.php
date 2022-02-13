<?php

namespace RTC\Http;

use RTC\Contracts\Http\RequestInterface;
use RTC\Contracts\Http\ResponseInterface;
use Swoole\Http\Response as Http1Response;
use Swoole\Http2\Response as Http2Response;

class Response implements ResponseInterface
{
    public function __construct(
        protected RequestInterface            $request,
        protected Http1Response|Http2Response $response
    )
    {
    }

    public function json(object|array $data, int $status = 200, array $headers = []): void
    {
        $this->plain(
            json_encode($data),
            $status,
            array_merge(['Content-Type' => 'application/json'], $headers)
        );
    }

    public function plain(string $string, int $status = 200, array $headers = []): void
    {
        if ($this->response->isWritable()) {
            foreach ($headers as $key => $value) {
                $this->response->setHeader($key, $value);
            }

            $this->response->setHeader('Server', 'PHP_RTC');
            $this->response->setStatusCode($status);
            $this->response->end($string);
        }
    }

    public function html(string $code, int $status = 200, array $headers = []): void
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

        $this->plain(null, 302);
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