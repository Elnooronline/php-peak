<?php

namespace Elnooronline\Peak;

use Elnooronline\Peak\Http\Request;
use Elnooronline\Peak\Http\Response;

abstract class ConnectorlessRequest extends Request
{
    public function send(): Response
    {
        return (new GenericConnector())->send($this);
    }
}