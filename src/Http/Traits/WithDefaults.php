<?php

namespace Elnooronline\Peak\Http\Traits;

trait WithDefaults
{
    /**
     * @return array
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function defaultQuery(): array
    {
        return [];
    }

    /**
     * @return null
     */
    protected function defaultBody()
    {
        return null;
    }
}