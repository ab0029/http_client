<?php

namespace Young\HttpClient\Clients\Api\Common;

use Young\HttpClient\Clients\Api\Gateway;

class Client extends Gateway
{
    public function get(string $url, array $params = [], $requestAsync = false)
    {
        if ( !isset($params['query']) || !is_array($params['query']) ) {
            $params = [
                'query' => $params
            ];
        }
        return $this->send('GET', $url, $params, $requestAsync);
    }

    public function post(string $url, $params = [], $requestAsync = false)
    {
        if ( !isset($params['form_params']) || !is_array($params['form_params']) ) {
            $params = [
                'form_params' => $params
            ];
        }
        return $this->send('POST', $url, $params, $requestAsync);
    }
}