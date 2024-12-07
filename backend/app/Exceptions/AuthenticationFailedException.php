<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;


class AuthenticationFailedException extends Exception
{
    /**
     * The custom message for the exception.
     *
     * @var string
     */
    protected $message = 'Invalid credentials.';

    /**
     * The HTTP status code.
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_UNAUTHORIZED;

    /**
     * Create a new instance of the exception.
     *
     * @param string|null $message
     * @param int|null $statusCode
     */
    public function __construct($message = null, $statusCode = null)
    {
        // Allow overriding the default message and status code
        if ($message) {
            $this->message = $message;
        }
        if ($statusCode) {
            $this->statusCode = $statusCode;
        }

        parent::__construct($this->message);
    }

    /**
     * Get the HTTP status code associated with the exception.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
