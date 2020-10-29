<?php

namespace Young\HttpClient\Clients\Api;

use Young\HttpClient\Clients\ServiceContainer;
use Young\HttpClient\Exceptions\ClientException;
use Young\HttpClient\SDK;

/**
 * 通用客户端
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
    ];

    public function __call($method, array $params)
    {
        if ( method_exists(Common\Client::class, $method) ) {
            return $this['common']->{$method}(...$params);
        }

        throw new ClientException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ), SDK::INVALID_ARGUMENT);
    }
}