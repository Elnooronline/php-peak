<?php

namespace Elnooronline\Peak\Support\Contracts;

use Closure;

interface PipelineInterface
{
    public function send($passable): self;

    public function through(array $pipes): self;

    public function via(string $method): self;

    public function then(Closure $destination);
}
