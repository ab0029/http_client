<?php

namespace Young\HttpClient\Exceptions;

use Exception;
use RuntimeException;

/**
 * Class BaseException
 *
 * @package   Young\HttpClient\Exception
 */
abstract class BaseException extends Exception
{

    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
