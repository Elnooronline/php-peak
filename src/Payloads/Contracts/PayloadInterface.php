<?php

namespace Elnooronline\Peak\Payloads\Contracts;

interface PayloadInterface
{
    public function contentType();

    public function all();

    public function set(string $key, $value = null);

    public function merge(...$values);

    public function push($value);

    public function remove(string $key);

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function __toString();
}
