<?php

namespace Elnooronline\Peak\Support\Traits;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;

trait WithClient
{
    /**
     * The HTTP client instance.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     * @return WithClient|\Elnooronline\Peak\Connector
     */
    public function withClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function client(): ClientInterface
    {
        if (! $this->client instanceof ClientInterface) {
            $this->client = $this->defaultClient();
        }

        return $this->client;
    }

    /**
     * Define the default HTTP client instance.
     */
    protected function defaultClient(): ClientInterface
    {
        return Psr18ClientDiscovery::find();
    }
}