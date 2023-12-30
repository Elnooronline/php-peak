<?php

namespace Elnooronline\Peak\Http\Traits;

use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use Elnooronline\Peak\Payloads\JsonPayload;

trait WithBodyPayload
{
    private function createPayload(): PayloadInterface
    {
        if (method_exists($this, 'definePayload')) {
            return $this->definePayload();
        }

        return new JsonPayload(is_array($this->defaultBody()) ? $this->defaultBody() : []);
    }
}
