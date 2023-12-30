<?php

namespace Elnooronline\Peak\Decoders;

use Elnooronline\Peak\Decoders\Contracts\DecoderInterface;
use Elnooronline\Peak\Exceptions\NotDecodableException;
use Psr\Http\Message\ResponseInterface;

final class DecoderFactory
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public static function create(ResponseInterface $response): DecoderInterface
    {
        return (new self($response))->decode();
    }

    public function decode(): DecoderInterface
    {
        try {
            if ($this->isContentType('json')) {
                return $this->createJsonDecoder();
            }

            if ($this->isContentType('xml')) {
                return $this->createXmlDecoder();
            }
        } catch (\Exception $e) {
            //
        }

        throw NotDecodableException::create();
    }

    protected function createJsonDecoder(): JsonDecoder
    {
        return new JsonDecoder($this->response);
    }

    protected function createXmlDecoder(): XmlDecoder
    {
        return new XmlDecoder($this->response);
    }

    protected function isContentType(string $type)
    {
        return mb_strpos($this->response->getHeaderLine('Content-Type'), $type) !== false;
    }
}
