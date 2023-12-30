<?php

namespace Elnooronline\Peak\Exceptions;

use LogicException;

class NotDecodableException extends LogicException
{
    public static function create(string $message = 'Unable to decode the response body.'): self
    {
        return new self($message);
    }
}