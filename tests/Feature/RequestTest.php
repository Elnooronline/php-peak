<?php

namespace Elnooronline\Peak\Tests\Feature;

use Elnooronline\Peak\Tests\Mocks\Services\CurrentUtcRequest;
use Elnooronline\Peak\Tests\Mocks\Services\DummyConnector;
use Elnooronline\Peak\Tests\TestCase;

class RequestTest extends TestCase
{
    private $connector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connector = new DummyConnector();
    }

    public function test_sending_request_directly(): void
    {
        $request = new CurrentUtcRequest();

        $response = $request->send();

        $this->assertTrue($response->ok());

        $datetime = new \DateTime((string) $response);
        $this->assertSame((new \DateTime())->format('Y-m-d'), $datetime->format('Y-m-d'));
    }
}
