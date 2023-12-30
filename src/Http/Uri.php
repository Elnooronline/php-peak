<?php

namespace Elnooronline\Peak\Http;

use Elnooronline\Peak\Support\Map;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

class Uri
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var UriInterface
     */
    protected UriInterface $uri;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->buildUri();
    }

    /**
     * @param int $port
     * @return $this
     */
    public function port(int $port): self
    {
        $this->uri->withPort($port);

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function append(string $path): self
    {
        $this->uri->withPath($path);

        return $this;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function scheme(string $scheme): self
    {
        $this->uri->withScheme($scheme);

        return $this;
    }

    /**
     * @return $this
     */
    public function http(): self
    {
        return $this->scheme('http');
    }

    /**
     * @return $this
     */
    public function https(): self
    {
        return $this->scheme('https');
    }

    /**
     * @param string $relative
     * @return $this|self
     */
    public function withBase($relative): self
    {
        if (! is_string($relative))
            return $this;

        $relative = $this->toPsrUri($relative);

        if ((string) $relative === '') {
            return $this;
        }

        if ($relative->getScheme() != '') {
            return $this->setUri(
                $relative->withPath($this->removeDotSegments($this->uri->getPath()))
            );
        }

        if ($relative->getAuthority() != '') {
            $targetAuthority = $relative->getAuthority();
            $targetPath = $this->removeDotSegments($relative->getPath());
            $targetQuery = $relative->getQuery();
        } else {
            $targetAuthority = $this->uri->getAuthority();

            if ($relative->getPath() === '') {
                $targetPath = $this->uri->getPath();
                $targetQuery = $relative->getQuery() != '' ? $relative->getQuery() : $this->uri->getQuery();
            } else {
                if ($relative->getPath()[0] === '/') {
                    $targetPath = $relative->getPath();
                } else {
                    if ($targetAuthority != '' && $this->uri->getPath() === '') {
                        $targetPath = '/'.$relative->getPath();
                    } else {
                        $lastSlashPos = \strrpos($this->uri->getPath(), '/');
                        if ($lastSlashPos === false) {
                            $targetPath = $relative->getPath();
                        } else {
                            $targetPath = \substr($this->uri->getPath(), 0, $lastSlashPos + 1).$relative->getPath();
                        }
                    }
                }

                $targetPath = $this->removeDotSegments($targetPath);
                $targetQuery = $relative->getQuery();
            }
        }

        $uri = '';
        $scheme = $this->uri->getScheme();

        if ($scheme != '') {
            $uri .= $scheme.':';
        }

        if ($targetAuthority != '' || $scheme === 'file') {
            $uri .= '//'.$targetAuthority;
        }

        if ($targetAuthority != '' && $targetPath != '' && $targetPath[0] != '/') {
            $targetPath = '/'.$targetPath;
        }

        $uri .= $targetPath;

        if ($targetQuery != '') {
            $uri .= '?'.$targetQuery;
        }

        if ($relative->getFragment() != '') {
            $uri .= '#'.$relative->getFragment();
        }

        return $this->setUri($this->toPsrUri($uri));
    }

    /**
     * @param Map $query
     * @return $this
     */
    public function withQuery(Map $query): self
    {
        $this->uri->withQuery(http_build_query($query->all()));

        return $this;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function removeDotSegments(string $path)
    {
        if ($path === '' || $path === '/') {
            return $path;
        }

        $results = [];
        $segments = \explode('/', $path);
        foreach ($segments as $segment) {
            if ($segment === '..') {
                \array_pop($results);
            } elseif ($segment !== '.') {
                $results[] = $segment;
            }
        }

        $newPath = \implode('/', $results);

        if ($path[0] === '/' && (! isset($newPath[0]) || $newPath[0] !== '/')) {
            // Re-add the leading slash if necessary for cases like "/.."
            $newPath = '/'.$newPath;
        } elseif ($newPath !== '' && ($segment === '.' || $segment === '..')) {
            // Add the trailing slash if necessary
            // If newPath is not empty, then $segment must be set and is the last segment from the foreach
            $newPath .= '/';
        }

        return $newPath;
    }

    /**
     * @return self
     */
    protected function buildUri(): self
    {
        $this->setUri($this->toPsrUri($this->request->endpoint()));

        return $this;
    }

    /**
     * @param string $uri
     * @return UriInterface
     */
    protected function toPsrUri(string $uri): UriInterface
    {
        return Psr17FactoryDiscovery::findUriFactory()
            ->createUri($uri);
    }

    /**
     * @param UriInterface $uri
     * @return self
     */
    private function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->uri;
    }
}