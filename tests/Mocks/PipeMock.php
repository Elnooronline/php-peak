<?php

namespace Elnooronline\Peak\Tests\Mocks;

use Elnooronline\Peak\Support\Contracts\InvokablePipelineInterface;

class PipeMock implements InvokablePipelineInterface
{
    public function handle($passable, $next)
    {
        return $next($passable + 1);
    }

    public function addTwo($passable, $next)
    {
        return $next($passable + 2);
    }
}
