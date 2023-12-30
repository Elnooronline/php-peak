<?php

namespace Elnooronline\Peak\Payloads;

use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use Elnooronline\Peak\Support\Map;

class FormPayload extends Map implements PayloadInterface
{
    /**
     * 'Content-Type' header's value.
     *
     * @return string
     */
    public function contentType()
    {
        return 'application/json';
    }

    /**
     * String representation of given payload.
     *
     * @return false|string
     */
    public function __toString()
    {
        return http_build_query($this->all());
    }
}