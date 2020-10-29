<?php

namespace Young\HttpClient\Request;

use Countable;
use ArrayAccess;
use IteratorAggregate;
use GuzzleHttp\Psr7\Request as GuzzleHttpPsrRequest;
use Psr\Http\Message\RequestInterface;
use Young\HttpClient\Traits\HasDataTrait;

class PsrRequest extends GuzzleHttpPsrRequest implements ArrayAccess, IteratorAggregate, Countable
{
    use HasDataTrait;

    public function __construct(RequestInterface $request, array $options = [])
    {
        parent::__construct(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion()
        );

        $this->dot($options);
    }
}