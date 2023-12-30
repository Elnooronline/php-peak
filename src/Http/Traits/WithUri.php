<?php

namespace Elnooronline\Peak\Http\Traits;

use Elnooronline\Peak\Http\Uri;

trait WithUri
{
//    /**
//     * @return \Psr\Http\Message\UriInterface
//     */
//    public function getUri(): UriInterface
//    {
//        $uri = $this->createUri($this->endpoint());
//
//        return $this->query()->count()
//            ? $uri->withQuery(http_build_query($this->query()->all()))
//            : $uri;
//    }
//
//    /**
//     * @param string $uri
//     * @return UriInterface
//     */
//    protected function createUri(string $uri): UriInterface
//    {
//        return Psr17FactoryDiscovery::findUriFactory()
//            ->createUri($uri);
//    }

    /**
     * @return UriInterface
     */
    protected function createUri(): Uri
    {
        if (method_exists($this, 'defineUri')) {
            return $this->defineUri();
        }

        return new Uri($this);
    }
}