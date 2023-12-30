<?php

namespace Elnooronline\Peak\Tests\Mocks\Services;

use Elnooronline\Peak\ConnectorlessRequest;

class CurrentUtcRequest extends ConnectorlessRequest
{
    public function endpoint(): string
    {
        return 'https://postman-echo.com/time/now';
    }
}