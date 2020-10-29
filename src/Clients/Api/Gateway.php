<?php

namespace Young\HttpClient\Clients\Api;

use Pimple\Container;
use Young\HttpClient\Request\Request;

class Gateway extends Request
{
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;

        parent::__construct(
            (array) $app['config']->get('http', [])
        );
    }

    public function send(string $method, $uri, $params = [], $requestAsync = false)
    {
        $this->method = $method;
        $this->api_uri = $uri; 
        $this->api_params = $params;
        return $requestAsync ? $this->requestAsync()
                             : $this->request();
    }

    protected function resolveHost() 
    {
        $this->setUri($this->api_uri);
    }

    protected function resolveParameter() 
    {
        $this->options = array_merge($this->options, (array) $this->api_params);
    }
}