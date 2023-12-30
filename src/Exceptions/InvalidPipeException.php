<?php

namespace Elnooronline\Peak\Exceptions;

use LogicException;

class InvalidPipeException extends LogicException
{
    public static function create(string $message = 'The pipe must be an instance of InvokablePipelineInterface.'): self
    {
        return new self($message);
    }
}
