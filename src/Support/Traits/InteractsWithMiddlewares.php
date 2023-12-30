<?php

namespace Elnooronline\Peak\Support\Traits;

use Elnooronline\Peak\Middlewares\Middleware;

trait InteractsWithMiddlewares
{
    private $middlewares;

    public function middlewares(): Middleware
    {
        if (! $this->middlewares instanceof Middleware) {
            $this->middlewares = new Middleware($this->defaultMiddleware());
        }

        return $this->middlewares;
    }

    protected function defaultMiddleware(): array
    {
        return [];
    }
}