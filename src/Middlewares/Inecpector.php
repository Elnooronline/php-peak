<?php

namespace Elnooronline\Peak\Middlewares;

use Closure;
use Elnooronline\Peak\Support\Contracts\InvokablePipelineInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Inecpector implements InvokablePipelineInterface
{
    public static function request(Closure $callback): Closure
    {
        return static function (RequestInterface $request, callable $next) use ($callback): ResponseInterface {
            return $next($callback($request));
        };
    }

    public static function response(Closure $callback): Closure
    {
        return static function (RequestInterface $request, callable $next) use ($callback): ResponseInterface {
            return $callback($next($request));
        };
    }
}
