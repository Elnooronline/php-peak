<?php

namespace Elnooronline\Peak\Payloads;

use Elnooronline\Peak\Payloads\Contracts\PayloadInterface;
use LogicException;

class RawPayload implements PayloadInterface
{
    private $payload = '';

    public function __construct(string $payload = '')
    {
        $this->payload = $payload;
    }

    public function all(): string
    {
        return $this->payload;
    }

    public function set(string $key, $value = null)
    {
        if (! is_null($value)) {
            throw new LogicException('Raw body payload does not support set values with keys');
        }

        $this->payload = $key;

        return $this;
    }

    public function merge(...$values)
    {
        throw new LogicException('Raw body payload does not support to merge values.');
    }

    public function push($value)
    {
        $this->payload .= $value;

        return $this;
    }

    public function remove(string $key)
    {
        throw new LogicException('Raw body payload does not support to remove a value by key.');
    }

    public function isEmpty(): bool
    {
        return $this->payload === '';
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * 'Content-Type' header's value.
     *
     * @return string
     */
    public function contentType(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->payload;
    }
}
