<?php

namespace Elnooronline\Peak\Http;

use Elnooronline\Peak\Decoders\DecoderFactory;
use Elnooronline\Peak\Http\Traits\InteractsWithStatusCodes;
use Elnooronline\Peak\Support\Macroable;
use Psr\Http\Message\ResponseInterface;

class Response
{
    use Macroable;
    use InteractsWithStatusCodes;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * The decoded response.
     *
     * @var array
     */
    private $decoded;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Response raw body.
     */
    public function raw(): string
    {
        return (string) $this->response->getBody();
    }

    /**
     * Response decoded body.
     *
     * @return mixed
     */
    public function body()
    {
        if (! $this->decoded) {
            $this->decoded = $this->decode();
        }

        return $this->decoded;
    }

    /**
     * Get response headers.
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * Get response header.
     *
     * @param string $header
     * @return string|null
     */
    public function header(string $header): ?string
    {
        return $this->response->getHeaderLine($header) ?: null;
    }

    /**
     * Get the status code of the response.
     *
     * @return int
     */
    public function status(): int
    {
        return (int) $this->response->getStatusCode();
    }

    /**
     * @return string
     */
    public function reason(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * Get the underlying PSR response for the response.
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the body of the response.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->raw();
    }

    /**
     * Decode received response body.
     *
     * @return mixed
     */
    private function decode()
    {
        return DecoderFactory::create($this->response)
            ->decode();
    }
}
