<?php

namespace Elnooronline\Peak\Support;

use Closure;
use Elnooronline\Peak\Exceptions\InvalidPipeException;
use Elnooronline\Peak\Support\Contracts\InvokablePipelineInterface;
use Elnooronline\Peak\Support\Contracts\PipelineInterface;

final class Pipeline implements PipelineInterface
{
    /**
     * The object being passed through the pipeline.
     *
     * @var mixed
     */
    protected $passable;

    /**
     * Method that every pipe should have.
     *
     * @var string
     */
    protected $method = 'handle';

    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected $pipes = [];

    public static function create()
    {
        return new static();
    }

    public function send($passable): self
    {
        $this->passable = $passable;

        return $this;
    }

    public function through(array $pipes): self
    {
        $this->pipes = $pipes;

        return $this;
    }

    public function via(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function then(Closure $destination)
    {
        $pipeline = $destination;
        $pipes = array_reverse($this->pipes);

        foreach ($pipes as $pipe) {
            $pipeline = function ($passable) use ($pipe, $pipeline) {
                return $this->invokePipe($pipe, $passable, $pipeline);
            };
        }

        return $pipeline($this->passable);
    }

    public function thenReturn()
    {
        return $this->then(function ($passable) {
            return $passable;
        });
    }

    protected function invokePipe($pipe, $passable, $next)
    {
        if (is_callable($pipe))
            return $pipe($passable, $next);

        $method = $this->method;

        if (is_array($pipe) && count($pipe) == 2) {
            $method = $pipe[1];
            $pipe = $pipe[0];
        }

        if (is_object($pipe) && $pipe instanceof InvokablePipelineInterface)
            return $pipe->{$method}($passable, $next);

        if (is_string($pipe) && class_exists($pipe)) {
            $pipe = (new $pipe);

            if ($pipe instanceof InvokablePipelineInterface) {
                return (new $pipe)->{$method}($passable, $next);
            }
        }

        throw InvalidPipeException::create();
    }
}