<?php

namespace App\core\Responses;

class HTMLResponse implements IResponse
{
    private array $headers;
    private string $body;

    /**
     * @param array  $headers - headers of the response.
     * @param string $body    - body of the response.
     */
    public function __construct(array $headers, string $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return array - headers of the response.
     */
    final public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string - body of the response.
     */
    final public function getBody(): string
    {
        return $this->body;
    }
}
