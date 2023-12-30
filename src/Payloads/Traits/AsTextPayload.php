<?php

namespace Elnooronline\Peak\Payloads\Traits;

use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use Elnooronline\Peak\Payloads\RawPayload;

trait AsTextPayload
{
    protected function definePayload(): PayloadInterface
    {
        return new RawPayload($this->defaultBody() ?: '');
    }
}