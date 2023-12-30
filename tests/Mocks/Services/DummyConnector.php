<?php

namespace Elnooronline\Peak\Tests\Mocks\Services;

use Elnooronline\Peak\Connector;

class DummyConnector extends Connector
{
    public function baseUri(): ?string
    {
        return 'https://httpbin.org';
    }

    protected function defaultMiddleware(): array
    {
        return [];
    }
}
