<?php

namespace Elnooronline\Peak\Middlewares;

use Countable;

use Elnooronline\Peak\Support\Traits\InteractsWithMiddlewares;
use IteratorAggregate;

final class Middleware implements Countable, IteratorAggregate
{
    private array $middlewares = [];

    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->middlewares;
    }

    /**
     * @param callable|string|object $middleware
     * @param string $method
     * @return \Elnooronline\Peak\Connector|InteractsWithMiddlewares
     */
    public function push($middleware, string $method = null): self
    {
        if (is_callable($middleware)) {
            $this->middlewares[] = $middleware;
        } else {
            $this->middlewares[] = [$middleware, $method];
        }

        return $this;
    }

    /**
     * @param callable|string|object $middleware
     * @param string $method
     * @return $this
     */
    public function prepend($middleware, string $method = null): self
    {
        array_unshift(
            $this->middlewares,
            is_callable($middleware) ? $middleware : [$middleware, $method]
        );

        return $this;
    }

    public function remove($remove): self
    {
        $idx = is_callable($remove) ? 0 : 1;

        $this->middlewares = array_values(
            array_filter(
                $this->middlewares,
                static function ($tuple) use ($idx, $remove) {
                    return $tuple[$idx] !== $remove;
                }
            )
        );

        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->middlewares);
    }

    public function count(): int
    {
        return count($this->middlewares);
    }

    public function isEmpty(): bool
    {
        return $this->count() <= 0;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @return array
     */
    public function onlyActive(): array
    {
        return array_filter(array_map(function ($item) {
            if (is_array($item)) {
                return $item[0] ?? null;
            }

            return $item ?? null;
        }, $this->all()));
    }
}
