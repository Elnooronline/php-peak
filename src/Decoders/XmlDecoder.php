<?php

namespace Elnooronline\Peak\Decoders;

use Elnooronline\Peak\Decoders\Contracts\DecoderInterface;
use Psr\Http\Message\ResponseInterface;

final class XmlDecoder implements DecoderInterface
{
    private ResponseInterface $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function decode(): array
    {
        $xml = simplexml_load_string((string) $this->response->getBody());

        if (! $xml) {
            return [];
        }

        return json_decode(
            json_encode($xml) ?: '[]', true
        );
    }
}