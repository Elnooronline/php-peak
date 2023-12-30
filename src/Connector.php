<?php

namespace Elnooronline\Peak;

use Elnooronline\Peak\Http\Request;
use Elnooronline\Peak\Http\Response;
use Elnooronline\Peak\Support\Contracts\PipelineInterface;
use Elnooronline\Peak\Support\Pipeline;
use Elnooronline\Peak\Support\Traits\InteractsWithMiddlewares;
use Elnooronline\Peak\Support\Traits\WithClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Connector
{
    use WithClient;
    use InteractsWithMiddlewares;

    abstract public function baseUri(): ?string;

    /**
     * @param Request $request
     * @return Response
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function send(Request $request): Response
    {
        $request = $this->createRequest($request);

        return new Response($this->sendRequest($request));
    }

    /**
     * @param Request $request
     * @return RequestInterface
     */
    protected function createRequest(Request $request): RequestInterface
    {
        $baseUri = (string) $request->uri()
            ->withBase($this->baseUri())
            ->withQuery($request->query());

        $psrRequest = Psr17FactoryDiscovery::findRequestFactory()
            ->createRequest(
                $request->method(),
                $baseUri
            );

        if (
            ! $request->headers()->has('Content-Type') &&
            ($contentType = $request->body()->contentType())
        ) {
            $request->headers()->set('Content-Type', $contentType);
        }

        if ($request->headers()->isNotEmpty()) {
            foreach ($request->headers() as $name => $value) {
                $psrRequest = $psrRequest->withHeader($name, $value);
            }
        }

        $psrRequest->withProtocolVersion($request->version())->withBody(
            Psr17FactoryDiscovery::findStreamFactory()
                ->createStream((string) $request->body())
        );

        return $psrRequest;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->pipeline()
            ->send($request)
            ->through($this->middlewares()->all())
            ->then(function (RequestInterface $request) {
                return $this->client()->sendRequest($request);
            });
    }

    /**
     * @return PipelineInterface
     */
    protected function pipeline(): PipelineInterface
    {
        return Pipeline::create();
    }
}
