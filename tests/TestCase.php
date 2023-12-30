<?php

namespace Elnooronline\Peak\Tests;

use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    private $responseFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->responseFactory = new Psr17FactoryDiscovery();
    }
}
