<?php

namespace App\core\Responses;

use App\Core\Model;

class JSONResponse implements IResponse
{
    private array $headers;
    private Model $body;

    /**
     * @param array $headers - headers of the response.
     * @param string $body - body of the response.
     */
    public function __construct(array $headers, Model $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Get response headers.
     * @return array - headers of the response.
     */
    final public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get response body.
     * @return string - body of the response.
     * @throws \JsonException
     */
    final public function getBody(): string
    {
        return json_encode($this->body, JSON_THROW_ON_ERROR);
    }
}
