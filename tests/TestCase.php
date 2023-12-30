<?php

namespace Elnooronline\Peak\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Http\Discovery\Psr17FactoryDiscovery;

class TestCase extends BaseTestCase
{
    private $responseFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->responseFactory = new Psr17FactoryDiscovery();
    }
}