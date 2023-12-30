<?php

namespace Elnooronline\Peak\Http;

use Elnooronline\Peak\Http\Traits\WithBodyPayload;
use Elnooronline\Peak\Http\Traits\WithDefaults;
use Elnooronline\Peak\Http\Traits\WithUri;
use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use Elnooronline\Peak\Support\Map;

abstract class Request
{
    use WithDefaults;
    use WithBodyPayload;
    use WithUri;

    /**
     * @var Map
     */
    private $headers;

    /**
     * @var Map
     */
    private $query;

    /**
     * @var PayloadInterface
     */
    private $body;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * Request endpoint.
     *
     * @return string
     */
    abstract public function endpoint(): string;

    /**
     * Request method.
     *
     * @return string
     */
    public function method(): string
    {
        return 'GET';
    }

    /**
     * Request version
     *
     * @return string
     */
    public function version(): string
    {
        return '1.1';
    }

    /**
     * Request headers.
     *
     * @return Map
     */
    public function headers(): Map
    {
        if (! $this->headers instanceof Map) {
            $this->headers = new Map($this->defaultHeaders());
        }

        return $this->headers;
    }

    /**
     * Request query string.
     *
     * @return Map
     */
    public function query(): Map
    {
        if (! $this->query instanceof Map) {
            $this->query = new Map($this->defaultQuery());
        }

        return $this->query;
    }

    /**
     * Request body.
     *
     * @return PayloadInterface
     */
    public function body(): PayloadInterface
    {
        if (! $this->body instanceof PayloadInterface) {
            $this->body = $this->createPayload();
        }

        return $this->body;
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        if (! $this->uri instanceof Uri) {
            $this->uri = $this->createUri();
        }

        return $this->uri;
    }
}