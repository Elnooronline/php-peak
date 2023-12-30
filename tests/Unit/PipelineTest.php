<?php

namespace Elnooronline\Peak\Tests\Unit;

use Elnooronline\Peak\Exceptions\InvalidPipeException;
use Elnooronline\Peak\Support\Pipeline;
use Elnooronline\Peak\Tests\Mocks\PipeMock;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function test_empty_pipeline_should_work()
    {
        $result = Pipeline::create()
            ->send(1)
            ->then(function ($passable) {
                return $passable + 2;
            });

        $this->assertEquals($result, 3);
    }

    public function test_run_pipeline_with_function()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                function ($passable, $next) {
                    return $next($passable + 1);
                },
            ])
            ->thenReturn();

        $this->assertEquals($result, 2);
    }

    public function test_run_pipeline_using_class_instance()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                new PipeMock(),
            ])
            ->thenReturn();

        $this->assertEquals($result, 2);
    }

    public function test_run_pipeline_using_class_instance_specific_method()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                new PipeMock(),
            ])
            ->via('addTwo')
            ->thenReturn();

        $this->assertEquals($result, 3);
    }

    public function test_run_pipeline_using_class_name()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                PipeMock::class,
            ])
            ->thenReturn();

        $this->assertEquals($result, 2);
    }

    public function test_run_pipeline_using_class_name_specific_method()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                PipeMock::class,
            ])
            ->via('addTwo')
            ->thenReturn();

        $this->assertEquals($result, 3);
    }

    public function test_run_pipeline_using_array()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                [PipeMock::class, 'handle'],
                [new PipeMock(), 'addTwo'],
            ])
            ->thenReturn();

        $this->assertEquals($result, 4);
    }

    public function test_run_pipeline_throguh_multiple_pipe_types()
    {
        $result = Pipeline::create()
            ->send(1)
            ->through([
                function ($passable, $next) {
                    return $next($passable + 1);
                },
                new PipeMock(),
                PipeMock::class,
                [PipeMock::class, 'handle'],
                [new PipeMock(), 'addTwo'],
            ])
            ->thenReturn();

        $this->assertEquals($result, 7);
    }

    public function test_throw_exception_on_invalid_pipe_type()
    {
        $this->expectException(InvalidPipeException::class);

        Pipeline::create()
            ->send(1)
            ->through([
                'non-existing-class',
            ])
            ->then(function ($passable) {
                return $passable;
            });
    }
}
