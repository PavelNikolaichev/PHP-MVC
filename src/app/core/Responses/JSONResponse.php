<?php

namespace App\core\Responses;

use JsonException;

class JSONResponse implements IResponse
{
    private array $headers;
    private array $body;

    /**
     * Constructor for JSONResponse.
     *
     * @param array $headers - headers of the response.
     * @param array $body    - body of the response.
     */
    public function __construct(array $headers, array $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Get response headers.
     *
     * @return array - headers of the response.
     */
    final public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get response body.
     *
     * @throws JsonException
     *
     * @return string - JSON-encoded body of the response.
     */
    final public function getBody(): string
    {
        return json_encode($this->body, JSON_THROW_ON_ERROR);
    }
}
