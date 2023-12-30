<?php

namespace Elnooronline\Peak\Payloads\Traits;

use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use Elnooronline\Peak\Payloads\JsonPayload;

trait AsJsonPayload
{
    /**
     * Create JSON request body.
     *
     * @return PayloadInterface
     */
    protected function definePayload(): PayloadInterface
    {
        return new JsonPayload(
            is_array($this->defaultBody()) ? $this->defaultBody() : []
        );
    }
}