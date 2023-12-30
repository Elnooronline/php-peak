<?php

namespace Elnooronline\Peak\Decoders;

use Elnooronline\Peak\Decoders\Contracts\DecoderInterface;
use Elnooronline\Peak\Support\Map;
use Psr\Http\Message\ResponseInterface;

final class JsonDecoder implements DecoderInterface
{
    private $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return Map
     */
    public function decode(): Map
    {
        return new Map(json_decode((string) $this->response->getBody(), true) ?? []);
    }
}