<?php

namespace Elnooronline\Peak\Http\Traits;

trait InteractsWithStatusCodes
{
    public function successful(): bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    public function ok(): bool
    {
        return $this->status() === 200;
    }

    public function redirect(): bool
    {
        return $this->status() >= 300 && $this->status() < 400;
    }

    public function unauthorized(): bool
    {
        return $this->status() === 401;
    }

    public function forbidden(): bool
    {
        return $this->status() === 403;
    }

    public function clientError(): bool
    {
        return $this->status() >= 400 && $this->status() < 500;
    }

    public function serverError(): bool
    {
        return $this->status() >= 500;
    }

    public function failed(): bool
    {
        return $this->serverError() || $this->clientError();
    }
}
