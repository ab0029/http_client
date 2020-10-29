<?php

namespace Young\HttpClient\Result;

use Countable;
use Exception;
use ArrayAccess;
use IteratorAggregate;
use InvalidArgumentException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Young\HttpClient\Request\Request;
use Young\HttpClient\Traits\HasDataTrait;

class Result extends Response implements ArrayAccess, IteratorAggregate, Countable
{
    use HasDataTrait;

    /**
     * Instance of the request.
     *
     * @var Request
     */
    protected $request;

    /**
     * psr Request
     * 
     * @var RequestInterface
     */
    protected $psrRequest;

    /**
     * Response Body Content
     * @var String
     */
    protected $content;

    /**
     * Result constructor.
     *
     * @param ResponseInterface $response
     * @param Request           $request
     */
    public function __construct(ResponseInterface $response, RequestInterface $psrRequest, Request $request = null)
    {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );

        $this->psrRequest = $psrRequest;

        $this->request = $request;

        $this->resolveData();
    }

    protected function resolveData()
    {
        $content = $this->getBody()->getContents();

        $this->content = $content;

        switch ($this->getRequestFormat()) {
            case 'JSON':
                $result_data = $this->jsonToArray($content);
                break;
            case 'XML':
                $result_data = $this->xmlToArray($content);
                break;
            default:
                $result_data = $content;
        }

        $this->dot(is_array($result_data) ? $result_data : []);
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    protected function getRequestFormat()
    {
        return ($this->request instanceof Request)
            ? \strtoupper($this->request->format)
            : 'JSON';
    }

    /**
     * @param string $response
     *
     * @return array
     */
    protected function jsonToArray($response)
    {
        try {
            return \GuzzleHttp\json_decode($response, true);
        } catch (InvalidArgumentException $exception) {
            return [];
        }
    }

    /**
     * @param string $string
     *
     * @return array
     */
    protected function xmlToArray($string)
    {
        try {
            return json_decode(json_encode(simplexml_load_string($string)), true);
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getBody();
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function setPsrRequest(RequestInterface $psrRequest)
    {
        $this->psrRequest = $psrRequest;
        return $this;
    }

    public function getPsrRequest()
    {
        return $this->psrRequest;
    }

    public function raw()
    {
        return $this->__toString();
    }

    /**
     * @codeCoverageIgnore
     * @return Response
     * @deprecated
     */
    public function getResponse()
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isStatusSuccess();
    }

    public function isStatusSuccess()
    {
        return 200 <= $this->getStatusCode()
               && 300 > $this->getStatusCode();
    }
}
