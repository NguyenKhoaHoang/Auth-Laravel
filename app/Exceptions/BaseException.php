<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var string
     */
    protected $messageCode = null;

    public function setArgs(array $args)
    {
        $this->args = $args;

        return $this;
    }

    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Set the message code
     *
     * @param string $code
     * @return self
     */
    public function setMessageCode(string $code)
    {
        $this->messageCode = $code;

        return $this;
    }

    /**
     * Get the message code
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Create new exception instance with code
     *
     * @return self
     */
    public static function code($code, $args = [], $statusCode = 400)
    {
        return (new static(__('messages.' . $code, $args), $statusCode))->setMessageCode($code);
    }
}
